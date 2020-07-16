<?php

namespace Webflorist\FormFactory\Utilities\AjaxValidation;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Webflorist\FormFactory\Exceptions\MandatoryOptionMissingException;
use Webflorist\FormFactory\Utilities\AntiBotProtection\CaptchaProtection;
use Webflorist\FormFactory\Utilities\FormFactoryTools;
use Validator;

class AjaxValidationController extends Controller
{
    public function __construct()
    {
        //Load middleware normale is loaded by the action route
        if(request()->has('_middleware')) {
            if (!is_array($middleware = request()->get('_middleware'))) {
                $middleware = [$middleware];
            }

            $this->middleware($middleware);
        }
    }

    /**
     * Controller-method for ajax-validation.
     *
     * @return JsonResponse
     * @throws \Webflorist\FormFactory\Exceptions\FormRequestClassNotFoundException
     * @throws MandatoryOptionMissingException
     */
    public function process()
    {

        // Ajax-validation is only possible, if the _formID was submitted (automatically done by the FormFactory).
        if (!request()->has('_formID')) {
            throw new MandatoryOptionMissingException('Ajax validation was not possible due to missing "_formID" field in request.');
        }

        // The FormFactory should have saved the requestObject this form uses inside the session.
        // We check, if it is there, and can continue only, if it is.
        $sessionKeyForRequestObject = 'formfactory.request_objects.' . request()->input('_formID');

        if (!session()->has($sessionKeyForRequestObject)) {
            throw new MandatoryOptionMissingException('Ajax validation of form with ID "' . request()->input('_formID') . '" was not possible due to missing request-object-info in session.');
        }
        $formRequestObjectClass = session()->get($sessionKeyForRequestObject);

        // Normally we assume a successful submission and return just an empty JSON-array.
        $returnCode = 200;
        $return = [];

        // We instantiate the requestObject and make sure,
        // it is save in session as last_form_request_object,
        // since captcha-validation uses this info.
        $formRequest = FormFactoryTools::initFormRequestObject($formRequestObjectClass);
        FormFactoryTools::saveLastFormRequestObject($formRequestObjectClass);

        // We instantiate a controller with the submitted request-data
        // and the rules and messages from the requestObject.
        $validator = Validator::make(
            request()->all(),
            $formRequest->rules(),
            $formRequest->messages(),
            $formRequest->attributes()
        );

        // We also make sure, any current captcha-answer for this request-object stays in session.
        CaptchaProtection::reflashCaptchaData($formRequestObjectClass);

        // Perform validation, extract error-messages for all fields on failure, put them inside a $return['errors']-array, and return status code 422.
        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();
            $return['errors'] = $this->convertArrayFieldDotNotations2HtmlNames($errors);
            $returnCode = 422;
        }

        return new JsonResponse($return, $returnCode);

    }

    private function convertArrayFieldDotNotations2HtmlNames($errors)
    {
        $return = [];
        foreach ($errors as $fieldName => $errorMessages) {
            $return[FormFactoryTools::convertArrayFieldDotNotation2HtmlName($fieldName)] = $errorMessages;
        }
        return $return;
    }
}

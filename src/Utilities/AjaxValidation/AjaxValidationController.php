<?php

namespace Nicat\FormBuilder\Utilities\AjaxValidation;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Nicat\FormBuilder\Exceptions\MandatoryOptionMissingException;
use Nicat\FormBuilder\Utilities\FormBuilderTools;
use Validator;

class AjaxValidationController extends Controller
{

    /**
     * Controller-method for ajax-validation.
     *
     * @return JsonResponse
     * @throws \Nicat\FormBuilder\Exceptions\FormRequestClassNotFoundException
     * @throws MandatoryOptionMissingException
     */
    public function process()
    {

        // Ajax-validation is only possible, if the _formID was submitted (automatically done by the FormBuilder).
        if (!request()->has('_formID')) {
            throw new MandatoryOptionMissingException('Ajax validation was not possible due to missing "_formID" field in request.');
        }

        // The FormBuilder should have saved the requestObject this form uses inside the session.
        // We check, if it is there, and can continue only, if it is.
        $sessionKeyForRequestObject = 'formbuilder.request_objects.' . request()->input('_formID');

        if (!session()->has($sessionKeyForRequestObject)) {
            throw new MandatoryOptionMissingException('Ajax validation of form with ID "' . request()->input('_formID') . '" was not possible due to missing request-object-info in session.');
        }

        // Normally we assume a successful submission and return just an empty JSON-array.
        $returnCode = 200;
        $return = [];

        // We instantiate the requestObject.
        $formRequest = FormBuilderTools::initFormRequestObject(session()->get($sessionKeyForRequestObject));

        // We instantiate a controller with the submitted request-data
        // and the rules and messages from the requestObject.
        $validator = Validator::make(
            request()->all(),
            $formRequest->rules(),
            $formRequest->messages(),
            $formRequest->attributes()
        );

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
            $return[FormBuilderTools::convertArrayFieldDotNotation2HtmlName($fieldName)] = $errorMessages;
        }
        return $return;
    }
}

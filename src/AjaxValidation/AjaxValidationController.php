<?php

namespace Nicat\FormBuilder\AjaxValidation;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Session;
use Validator;

class AjaxValidationController extends Controller
{

    public function process(Request $request) {

        // Ajax-validation is only possible, if the _formID was submitted (automatically done by the FormBuilder).
        if (\request()->has('_formID')) {

            // The FormBuilder should have saved the requestObject this form uses inside the session.
            // We check, if it is there, and can continue only, if it is.
            $sessionKeyForRequestObject = 'htmlBuilder.formbuilder.request_objects.'.\request()->input('_formID');

            if (Session::has($sessionKeyForRequestObject)) {

                // Normally we assume a successful submission and return just an empty JSON-array.
                $returnCode = 200;
                $return = [];

                // We instantiate the requestObject.
                $formRequest = FormBuilderTools::getRequestObject(Session::get($sessionKeyForRequestObject));

                // We instantiate a controller with the submitted request-data
                // and the rules and messages from the requestObject.
                $validator = Validator::make(
                    \request()->all(),
                    $formRequest->rules(),
                    $formRequest->messages(),
                    $formRequest->attributes()
                );

                // Perform validation, extract error-messages for all fields on failure, put them inside a $return['errors']-array, and return status code 422.
                if ($validator->fails()) {
                    $errors = $validator->errors()->getMessages();
                    $return['errors'] = $errors;
                    $returnCode = 422;
                }

                return new JsonResponse($return, $returnCode);

            }

        }
    }
}

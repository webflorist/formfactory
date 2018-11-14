<?php

namespace Nicat\FormFactory\Vue;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Nicat\FormFactory\Vue\Responses\VueFormErrorResponse;

/**
 * Use this Trait in your controllers
 * to create proper responses for VueForms.
 *
 * Class FormFactoryResponseTrait
 * @package Nicat\FormFactory
 */
trait FormFactoryControllerTrait
{

    /**
     * Validate the given request with the given rules.
     * Overridden to deliver correct response
     * when using VueForms.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  array $rules
     * @param  array $messages
     * @param  array $customAttributes
     * @return array
     * @throws ValidationException
     */
    public function validate(Request $request, array $rules,
                             array $messages = [], array $customAttributes = [])
    {

        try {
            return parent::validate($request, $rules, $messages, $customAttributes);
        }
        catch (ValidationException $originalException) {
            if ($request->wantsJson()) {
                (new VueFormErrorResponse($originalException->validator->errors()->messages()))
                    ->throwValidationException($originalException->validator);
            }
            throw $originalException;
        }

    }

}
<?php

namespace Nicat\FormFactory\Vue;

use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Nicat\FormFactory\Vue\Responses\VueFormErrorResponse;

/**
 * Use this Trait in your form request objects
 * to create proper responses for VueForms.
 *
 * Class FormFactoryResponseTrait
 * @package Nicat\FormFactory
 */
trait FormFactoryFormRequestTrait
{

    /**
     * @inheritDoc
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->wantsJson()) {
            (new VueFormErrorResponse($validator->errors()->messages()))->throwValidationException($validator);
        }
        parent::failedValidation($validator);
    }

}
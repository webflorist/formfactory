<?php

namespace Nicat\FormFactory\Vue;

use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;

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
            throw new ValidationException(
                $validator,
                FormFactoryResponse::error(
                    $validator->errors()->messages()
                )
            );
        }

        parent::failedValidation($validator);
    }

}
<?php

namespace Webflorist\FormFactory\Vue\Responses;

use Illuminate\Foundation\Testing\Assert as PHPUnit;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;

/**
 * Class to create a error-JsonResponse of a VueForm.
 *
 * Class VueFormResponse
 * @package Webflorist\FormFactory
 */
class VueFormErrorResponse extends VueFormResponse
{

    /**
     * VueFormErrorResponse constructor.
     *
     * @param array $errors
     * @param int $status
     */
    public function __construct(array $errors, int $status = 422)
    {
        parent::__construct(null, $status);
        $this->vueFormResponseData['errors'] = $errors;
    }

    /**
     * Throws this VueFormErrorResponse as a Laravel ValidationException.
     *
     * @param Validator $validator
     * @throws ValidationException
     */
    public function throwValidationException(Validator $validator)
    {
        throw new ValidationException(
            $validator,
            $this
        );
    }

    /**
     * Assert that the given field(s) have errors.
     *
     * @param array|string $fields
     * @return $this
     */
    public function assertErrors($fields)
    {
        $fields = (array) $fields;
        $errors = $this->vueFormResponseData['errors'];

        foreach ($fields as $field) {
            PHPUnit::assertArrayHasKey($field, $errors, "VueFormErrorResponse missing error: $field");
        }

        return $this;
    }

}
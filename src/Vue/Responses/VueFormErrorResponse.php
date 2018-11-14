<?php

namespace Nicat\FormFactory\Vue\Responses;

/**
 * Class to create a error-JsonResponse of a VueForm.
 *
 * Class VueFormResponse
 * @package Nicat\FormFactory
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

}
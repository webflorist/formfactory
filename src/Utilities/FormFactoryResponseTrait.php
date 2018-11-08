<?php

namespace Nicat\FormFactory\Utilities;

use Illuminate\Http\JsonResponse;

/**
 * Use this Trait in your controller to create proper responses
 * for vue-enabled forms.
 *
 * Class FormFactoryResponseTrait
 * @package Nicat\FormFactory
 */
trait FormFactoryResponseTrait
{

    protected function formFactorySuccess(string $message) {
        return new JsonResponse(
            [
                'message' => $message,
            ]
        );
    }

}
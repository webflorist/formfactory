<?php

namespace Nicat\FormFactory\Utilities\VueApp;

use Illuminate\Http\JsonResponse;

/**
 * Handles the response for submitting a vue-enabled form.
 *
 * Class VueAppGenerator
 * @package Nicat\FormFactory
 */
class VueAppResponse
{

    public $fieldErrors = [];

    public $generalErrors = [];

    public $redirect = null;

    public $message = null;

    public $clearFields = null;

}
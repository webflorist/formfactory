<?php

namespace FormFactoryTests\Browser\Controllers;

use FormFactoryTests\Browser\Requests\AjaxValidationTestRequest;
use Illuminate\Routing\Controller;

class AjaxValidationTestController extends Controller
{

    public function getOnFormSubmit()
    {
        return view('ajax_validation_on_form_submit');
    }

    public function getOnFieldChange()
    {
        return view('ajax_validation_on_field_change');
    }

    public function getOnFieldKeyUp()
    {
        return view('ajax_validation_on_field_key_up');
    }

    public function post(AjaxValidationTestRequest $request)
    {
        return 'validated';
    }

}

<?php

namespace FormFactoryTests\Browser\Controllers;

use FormFactoryTests\Browser\Requests\CaptchaTestRequest;
use Illuminate\Routing\Controller;
use Nicat\FormFactory\Vue\FormFactoryResponse;

class CaptchaTestController extends Controller
{

    public function get()
    {
        return view('captcha');
    }

    public function post(CaptchaTestRequest $request)
    {
        if (config('formfactory.vue.enabled') && config('formfactory.vue.default')) {
            return FormFactoryResponse::success('validated');
        }
        return 'validated';
    }

}

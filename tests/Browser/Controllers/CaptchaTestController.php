<?php

namespace FormFactoryTests\Browser\Controllers;

use FormFactoryTests\Browser\Requests\CaptchaTestRequest;
use Nicat\FormFactory\Vue\Responses\VueFormSuccessResponse;

class CaptchaTestController extends Controller
{

    public function get()
    {
        return view('captcha');
    }

    public function post(CaptchaTestRequest $request)
    {
        if ($request->wantsJson()) {
            return new VueFormSuccessResponse('validated');
        }
        return 'validated';
    }

}

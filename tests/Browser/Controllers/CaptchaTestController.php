<?php

namespace FormFactoryTests\Browser\Controllers;

use FormFactoryTests\Browser\Requests\CaptchaTestRequest;
use Illuminate\Routing\Controller;

class CaptchaTestController extends Controller
{

    public function get()
    {
        return view('captcha');
    }

    public function post(CaptchaTestRequest $request)
    {
        return ' validated';
    }

}

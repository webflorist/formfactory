<?php

namespace FormBuilderTests\Browser\Controllers;

use Form;
use FormBuilderTests\Browser\Requests\CaptchaRequest;
use FormBuilderTests\Browser\Requests\CaptchaTestRequest;
use Illuminate\Cache\RateLimiter;
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

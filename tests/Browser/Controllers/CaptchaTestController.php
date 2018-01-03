<?php

namespace HtmlBuilderTests\Browser\Controllers;

use Form;
use HtmlBuilderTests\Browser\Requests\CaptchaRequest;
use HtmlBuilderTests\Browser\Requests\CaptchaTestRequest;
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

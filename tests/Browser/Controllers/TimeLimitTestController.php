<?php

namespace HtmlBuilderTests\Browser\Controllers;

use Form;
use HtmlBuilderTests\Browser\Requests\TimeLimitTestRequest;
use Illuminate\Routing\Controller;

class TimeLimitTestController extends Controller
{

    public function get()
    {
        return view('time_limit');
    }

    public function post(TimeLimitTestRequest $request)
    {
        return 'validated';
    }

}

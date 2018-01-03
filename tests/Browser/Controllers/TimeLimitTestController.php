<?php

namespace FormBuilderTests\Browser\Controllers;

use Form;
use FormBuilderTests\Browser\Requests\TimeLimitTestRequest;
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

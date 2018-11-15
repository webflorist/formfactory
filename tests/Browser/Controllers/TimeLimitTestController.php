<?php

namespace FormFactoryTests\Browser\Controllers;

use FormFactoryTests\Browser\Requests\TimeLimitTestRequest;
use Webflorist\FormFactory\Vue\Responses\VueFormSuccessResponse;

class TimeLimitTestController extends Controller
{

    public function get()
    {
        return view('time_limit');
    }

    public function post(TimeLimitTestRequest $request)
    {
        if ($request->wantsJson()) {
            return new VueFormSuccessResponse('validated');
        }
        return 'validated';
    }

}

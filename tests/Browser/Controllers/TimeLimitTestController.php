<?php

namespace FormFactoryTests\Browser\Controllers;

use FormFactoryTests\Browser\Requests\TimeLimitTestRequest;
use Illuminate\Routing\Controller;
use Nicat\FormFactory\Vue\Responses\VueFormSuccessResponse;

class TimeLimitTestController extends Controller
{

    public function get()
    {
        return view('time_limit');
    }

    public function post(TimeLimitTestRequest $request)
    {
        if (config('formfactory.vue.enabled') && config('formfactory.vue.default')) {
            return new VueFormSuccessResponse('validated');
        }
        return 'validated';
    }

}

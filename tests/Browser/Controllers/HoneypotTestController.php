<?php

namespace FormFactoryTests\Browser\Controllers;

use FormFactoryTests\Browser\Requests\HoneypotTestRequest;
use Illuminate\Routing\Controller;
use Nicat\FormFactory\Vue\Responses\VueFormSuccessResponse;

class HoneypotTestController extends Controller
{

    public function getHoneypotViaRules()
    {
        return view('honeypot_via_rules');
    }

    public function getHoneypotViaRequestObject()
    {
        return view('honeypot_via_request_object');
    }

    public function post(HoneypotTestRequest $request)
    {
        if ($request->wantsJson()) {
            return new VueFormSuccessResponse('validated');
        }
        return 'validated';
    }

}

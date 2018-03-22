<?php

namespace FormFactoryTests\Browser\Controllers;

use Form;
use FormFactoryTests\Browser\Requests\HoneypotTestRequest;
use Illuminate\Routing\Controller;

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
        return 'validated';
    }

}

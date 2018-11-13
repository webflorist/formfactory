<?php

namespace FormFactoryTests\Browser\Controllers;

use FormFactoryTests\Browser\Requests\VueFormTestRequest;
use Illuminate\Routing\Controller;
use Nicat\FormFactory\Vue\Responses\VueFormSuccessResponse;

class VueFormTestController extends Controller
{

    public function getRedirect()
    {
        return view('vue_form', ['action' => '/vue-redirect']);
    }

    public function getRedirectTarget()
    {
        return 'redirected';
    }

    public function postRedirect(VueFormTestRequest $request)
    {
        return (new VueFormSuccessResponse('validated'))->redirect('/vue-redirect-target');
    }

}

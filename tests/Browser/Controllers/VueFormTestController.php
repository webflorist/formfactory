<?php

namespace FormFactoryTests\Browser\Controllers;

use FormFactoryTests\Browser\Requests\VueFormTestRequest;
use Illuminate\Http\Request;
use Webflorist\FormFactory\Vue\FormFactoryControllerTrait;
use Webflorist\FormFactory\Vue\Responses\VueFormSuccessResponse;

class VueFormTestController extends Controller
{
    use FormFactoryControllerTrait;

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

    public function getControllerErrors()
    {
        return view('vue_form', ['action' => '/vue-controller-errors']);
    }

    public function postControllerErrors(Request $request)
    {
        $this->validate($request,['myTextField' => 'required']);
        return 'success (or rather not)';
    }

}

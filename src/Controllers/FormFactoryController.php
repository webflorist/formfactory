<?php

namespace Webflorist\FormFactory\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class FormFactoryController extends Controller
{
    public function getCsrfToken(Request $request)
    {
        return response()->json(csrf_token());
    }
}

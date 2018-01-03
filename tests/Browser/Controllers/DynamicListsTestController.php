<?php

namespace HtmlBuilderTests\Browser\Controllers;

use Form;
use HtmlBuilderTests\Browser\Requests\DynamicListsTestRequest;
use Illuminate\Routing\Controller;

class DynamicListsTestController extends Controller
{

    public function get()
    {
        return view('dynamic_lists');
    }

    public function getWithDefaultValues()
    {
        return view('dynamic_lists',['values'=>[
            'outer_dynamic_list' => [
                0 => [
                    'text' => 'foobar',
                    'inner_dynamic_list' => [
                        0 => 'foo',
                        1 => 'bar'
                    ]
                ],
                1 => [
                    'text' => 'boofar',
                    'inner_dynamic_list' => [
                        0 => 'boo',
                        1 => 'far'
                    ]
                ]
            ]
        ]]);
    }

    public function post(DynamicListsTestRequest $request)
    {
        return 'validated';
    }

}

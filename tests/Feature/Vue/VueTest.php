<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class VueTest extends TestCase
{
    protected $openForm = false;
    protected $closeForm = false;
    protected $decorators = ['bootstrap:v4','vue:v2'];

    public function test_simple_vue_app()
    {
        \Form::open('myFormId');
        \Form::text('text');
        \Form::close();

        $vue = \Form::vue('myFormId')->generate();

        $this->assertEquals(
            'new Vue({"el":"#myFormId","data":{"fields":{"text":{"value":"","isRequired":false,"isDisabled":false}}}});',
            $vue
        );
    }


}
<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class VueTest extends TestCase
{
    protected $openForm = false;
    protected $closeForm = false;
    protected $decorators = ['bootstrap:v4','vue:v2'];

    public function testComplexTextInputComponentForBootstrap4()
    {
        \Form::open('myFormId');
        \Form::text('text');
        \Form::close();

        \Form::generateVueApp('myFormId');
    }


}
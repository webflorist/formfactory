<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4Vue2;

use FormFactoryTests\TestCase;

class ButtonTest extends TestCase
{

    protected $enableVue = true;
    protected $decorators = ['bootstrap:v4'];

    public function testSimple()
    {
        $element = \Form::button('myButtonName');

        $this->assertHtmlEquals(
            '
                <button type="button" name="myButtonName" class="btn btn-default" id="myFormId_myButtonName">MyButtonName</button>
            ',
            $element->generate()
        );
    }

}
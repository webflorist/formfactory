<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4Vue2;

use FormFactoryTests\TestCase;

class ButtonTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4_vue2';
    protected $decorators = ['bootstrap:v4'];
    protected $enableVue = true;

    public function testSimple()
    {
        $element = \Form::button('button');

        $this->assertHtmlEquals(
            '
                <button type="button" name="button" class="btn btn-default" id="myFormId_button">Button</button>
            ',
            $element->generate()
        );
    }

}
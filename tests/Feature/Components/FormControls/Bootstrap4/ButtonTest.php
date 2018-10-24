<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class ButtonTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4';
    protected $decorators = ['bootstrap:v4'];

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
<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class ButtonTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::button('button');

        $this->assertHtmlEquals(
            '
                <button type="button" name="button" id="myFormId_button">Button</button>
            ',
            $element->generate()
        );
    }

}
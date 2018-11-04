<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class ButtonTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::button('myFieldName');

        $this->assertHtmlEquals(
            '
                <button type="button" name="myFieldName" id="myFormId_myFieldName">MyFieldName</button>
            ',
            $element->generate()
        );
    }

}
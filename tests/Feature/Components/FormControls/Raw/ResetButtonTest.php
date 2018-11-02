<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class ResetButtonTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::reset('myFieldName');

        $this->assertHtmlEquals(
            '
                <button type="reset" name="myFieldName" id="myFormId_myFieldName">MyFieldName</button>
            ',
            $element->generate()
        );
    }

}
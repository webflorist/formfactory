<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class HiddenInputTest extends TestCase
{


    protected $decorators = ['bootstrap:v4'];

    public function testSimple()
    {
        $element = \Form::hidden('myFieldName');

        $this->assertHtmlEquals(
            '
                <input type="hidden" name="myFieldName" class="form-control" id="myFormId_myFieldName" />
            ',
            $element->generate()
        );
    }

}
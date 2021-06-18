<?php

namespace FormFactoryTests\Feature\Components\FormControls\Raw;

use FormFactoryTests\TestCase;

class HiddenInputTest extends TestCase
{



    public function testSimple()
    {
        $element = \Form::hidden('myFieldName');

        $this->assertHtmlEquals(
            '
                <input type="hidden" name="myFieldName" id="myFormId_myFieldName" />
            ',
            $element->generate()
        );
    }

}
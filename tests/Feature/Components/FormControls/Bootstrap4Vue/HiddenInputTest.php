<?php

namespace FormFactoryTests\Feature\Components\FormControls\Bootstrap4Vue;

class HiddenInputTest extends AbstractBootstrap4VueTest
{

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
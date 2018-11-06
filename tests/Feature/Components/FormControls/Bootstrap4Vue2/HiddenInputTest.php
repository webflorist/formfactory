<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4Vue2;

use FormFactoryTests\TestCase;

class HiddenInputTest extends TestCase
{

    protected $enableVue = true;
    protected $decorators = ['bootstrap:v4'];

    public function testSimple()
    {
        $element = \Form::hidden('myFieldName');

        $this->assertHtmlEquals(
            '
                <input type="hidden" name="myFieldName" class="form-control" id="myFormId_myFieldName"aria-describedby="myFormId_myFieldName_errors" v-model="fields[\'myFieldName\'].value" v-bind:required="fields[\'myFieldName\'].isRequired" v-bind:disabled="fields[\'myFieldName\'].isDisabled" v-bind:aria-invalid="fieldHasError(\'myFieldName\')" />
            ',
            $element->generate()
        );
    }

}
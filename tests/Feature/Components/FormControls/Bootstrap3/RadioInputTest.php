<?php

namespace FormFactoryTests\Feature\Components\FormControls\Bootstrap3;

use FormFactoryTests\TestCase;

class RadioInputTest extends TestCase
{


    protected $decorators = ['bootstrap:v3'];

    public function testSimple()
    {
        $element = \Form::radio('myValue', 'myFieldName');

        $this->assertHtmlEquals(
            '
                <div class="radio">
                    <label>
                        <input type="radio" name="myFieldName" value="myValue" id="myFormId_myFieldName_myValue" />
                        MyValue
                    </label>
                </div>
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::radio('myValue', 'myFieldName')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <div class="radio has-error">
                    <div id="myFormId_myFieldName_errors" class="alert alert-danger" role="alert">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <label>
                        <input type="radio" name="myFieldName" value="myValue" id="myFormId_myFieldName_myValue" required aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_myValue_helpText" aria-invalid="true" />
                        MyValue<sup>*</sup>
                    </label>
                    <small id="myFormId_myFieldName_myValue_helpText" class="text-muted small">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
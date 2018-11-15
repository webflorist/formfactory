<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class RadioInputTest extends TestCase
{


    protected $decorators = ['bootstrap:v4'];

    public function testSimple()
    {
        $element = \Form::radio('myValue', 'myFieldName');

        $this->assertHtmlEquals(
            '
                <div class="form-group form-check">
                    <input type="radio" name="myFieldName" class="form-check-input" value="myValue" id="myFormId_myFieldName_myValue" />
                    <label class="form-check-label" for="myFormId_myFieldName_myValue"> MyValue </label>
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
                <div class="form-group form-check has-error">
                    <div role="alert" id="myFormId_myFieldName_errors" class="alert m-b-1 alert-danger">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="radio" name="myFieldName" class="form-check-input" value="myValue" id="myFormId_myFieldName_myValue" required aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_myValue_helpText" aria-invalid="true" />
                    <label class="form-check-label" for="myFormId_myFieldName_myValue"> MyValue <sup>*</sup> </label>
                    <small id="myFormId_myFieldName_myValue_helpText" class="text-muted form-text small">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
<?php

namespace FormFactoryTests\Feature\Components\FormControls\Bootstrap4;

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
                    <input type="radio" name="myFieldName" value="myValue" id="myFormId_myFieldName_myValue" class="form-check-input" />
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
                <div class="form-group custom-control custom-radio">
                    <div id="myFormId_myFieldName_errors" role="alert" class="invalid-feedback d-block">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="radio" name="myFieldName" value="myValue" id="myFormId_myFieldName_myValue" class="form-check-input" required aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_myValue_helpText" aria-invalid="true" />
                    <label class="form-check-label" for="myFormId_myFieldName_myValue"> MyValue <sup>*</sup> </label>
                    <small id="myFormId_myFieldName_myValue_helpText" class="text-muted form-text small">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
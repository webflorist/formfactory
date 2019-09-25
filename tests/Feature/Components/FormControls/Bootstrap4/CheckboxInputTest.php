<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class CheckboxInputTest extends TestCase
{


    protected $decorators = ['bootstrap:v4'];

    public function testSimple()
    {
        $element = \Form::checkbox('myFieldName', 'myValue');

        $this->assertHtmlEquals(
            '
                <div class="form-group custom-control custom-checkbox">
                    <input type="checkbox" name="myFieldName" class="custom-control-input" value="myValue" id="myFormId_myFieldName" />
                    <label class="custom-control-label" for="myFormId_myFieldName"> MyFieldName </label>
                </div>
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::checkbox('myFieldName', 'myValue')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <div class="form-group custom-control custom-checkbox">
                    <div id="myFormId_myFieldName_errors" role="alert" class="invalid-feedback">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="checkbox" name="myFieldName" class="custom-control-input is-invalid" value="myValue" id="myFormId_myFieldName" required aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" aria-invalid="true" />
                    <label class="custom-control-label" for="myFormId_myFieldName"> MyFieldName <sup>*</sup> </label>
                    <small id="myFormId_myFieldName_helpText" class="text-muted form-text small">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
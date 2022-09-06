<?php

namespace FormFactoryTests\Feature\Components\FormControls\Bootstrap4;

use FormFactoryTests\TestCase;

class RadioGroupTest extends TestCase
{

    protected $decorators = ['bootstrap:v4'];

    public function testSimple()
    {
        $element = \Form::radioGroup('myFieldName', [
            \Form::radio('myValue1'),
            \Form::radio('myValue2'),
        ]);

        $this->assertHtmlEquals(
            '
                <fieldset class="form-group">
                    <legend>MyFieldName</legend>
                    <div class="form-check">
                        <input type="radio" name="myFieldName" value="myValue1" id="myFormId_myFieldName_myValue1" class="form-check-input" />
                        <label class="form-check-label" for="myFormId_myFieldName_myValue1">MyValue1</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="myFieldName" value="myValue2" id="myFormId_myFieldName_myValue2" class="form-check-input" />
                        <label class="form-check-label" for="myFormId_myFieldName_myValue2">MyValue2</label>
                    </div>
                </fieldset>
            ',
            $element->generate()
        );
    }


    public function testComplex()
    {
        $element = \Form::radioGroup('myFieldName', [
            \Form::radio('myValue1'),
            \Form::radio('myValue2'),
        ])->legend('myLegend')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <fieldset class="form-group">
                    <legend>myLegend<sup>*</sup></legend>
                    <div role="alert" id="myFormId_myFieldName_errors" class="alert m-b-1 alert-danger">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <div class="form-check has-error">
                        <input type="radio" name="myFieldName" value="myValue1" id="myFormId_myFieldName_myValue1" class="form-check-input" aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" aria-invalid="true" required />
                        <label class="form-check-label" for="myFormId_myFieldName_myValue1">MyValue1</label>                 
                    </div>
                    <div class="form-check has-error">
                        <input type="radio" name="myFieldName" value="myValue2" id="myFormId_myFieldName_myValue2" class="form-check-input" aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" aria-invalid="true" required />
                        <label class="form-check-label" for="myFormId_myFieldName_myValue2">MyValue2</label>                    
                    </div>
                    <small id="myFormId_myFieldName_helpText" class="text-muted form-text small">myHelpText</small>
                </fieldset>
            ',
            $element->generate()
        );
    }


}
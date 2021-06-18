<?php

namespace FormFactoryTests\Feature\Components\FormControls\Bootstrap3;

use FormFactoryTests\TestCase;

class RadioGroupTest extends TestCase
{

    protected $decorators = ['bootstrap:v3'];

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
                    <div class="radio">
                        <label>
                            <input type="radio" name="myFieldName" value="myValue1" id="myFormId_myFieldName_myValue1" />
                            MyValue1
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="myFieldName" value="myValue2" id="myFormId_myFieldName_myValue2" />
                            MyValue2
                        </label>
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
                    <div id="myFormId_myFieldName_errors" class="alert alert-danger" role="alert">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <div class="radio has-error">
                        <label>
                            <input type="radio" name="myFieldName" value="myValue1" id="myFormId_myFieldName_myValue1" aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" aria-invalid="true" required />
                            MyValue1
                        </label>
                    </div>
                    <div class="radio has-error">
                        <label>
                            <input type="radio" name="myFieldName" value="myValue2" id="myFormId_myFieldName_myValue2" required aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" aria-invalid="true" />
                            MyValue2
                        </label>
                    </div>
                    <small id="myFormId_myFieldName_helpText" class="text-muted small">myHelpText</small>
                </fieldset>
            ',
            $element->generate()
        );
    }


}
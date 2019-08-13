<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class RadioGroupTest extends TestCase
{

    public function testSimple()
    {
        $element = \Form::radioGroup('myFieldName', [
            \Form::radio('myValue1'),
            \Form::radio('myValue2'),
        ]);

        $this->assertHtmlEquals(
            '
                <fieldset>
                    <legend>MyFieldName</legend>
                    <div>
                        <input type="radio" name="myFieldName" value="myValue1" id="myFormId_myFieldName_myValue1" />
                        <label for="myFormId_myFieldName_myValue1">MyValue1</label>
                    </div>
                    <div>
                        <input type="radio" name="myFieldName" value="myValue2" id="myFormId_myFieldName_myValue2" />
                        <label for="myFormId_myFieldName_myValue2">MyValue2</label>
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
                <fieldset>
                    <legend>myLegend<sup>*</sup></legend>
                    <div id="myFormId_myFieldName_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <div>
                        <input type="radio" name="myFieldName" value="myValue1" id="myFormId_myFieldName_myValue1" aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" aria-invalid="true" required />
                        <label for="myFormId_myFieldName_myValue1">MyValue1</label>                 
                    </div>
                    <div>
                        <input type="radio" name="myFieldName" value="myValue2" id="myFormId_myFieldName_myValue2" aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" aria-invalid="true" required />
                        <label for="myFormId_myFieldName_myValue2">MyValue2</label>                    
                    </div>
                    <small id="myFormId_myFieldName_helpText">myHelpText</small>
                </fieldset>
            ',
            $element->generate()
        );
    }


}
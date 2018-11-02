<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class RadioGroupTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::radioGroup('myFieldName', [
            \Form::radio('myValue1'),
            \Form::radio('myValue2'),
        ])->legend('myLegend');

        $this->assertHtmlEquals(
            '
                <fieldset>
                    <legend> myLegend </legend>
                    <input type="radio" name="myFieldName" value="myValue1" id="myFormId_myFieldName_myValue1" />
                    <label for="myFormId_myFieldName_myValue1"> MyValue1 </label>
                    <input type="radio" name="myFieldName" value="myValue2" id="myFormId_myFieldName_myValue2" />
                    <label for="myFormId_myFieldName_myValue2"> MyValue2 </label>
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
                    <legend> myLegend </legend>
                    <div id="myFormId_myFieldName_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="radio" name="myFieldName" value="myValue1" id="myFormId_myFieldName_myValue1" aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" aria-invalid="true" />
                    <label for="myFormId_myFieldName_myValue1">MyValue1</label>
                    <small id="myFormId_myFieldName_helpText">myHelpText</small>
                    <input type="radio" name="myFieldName" value="myValue2" id="myFormId_myFieldName_myValue2" aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" aria-invalid="true" />
                    <label for="myFormId_myFieldName_myValue2">MyValue2</label>
                    <small id="myFormId_myFieldName_helpText">myHelpText</small> <small id="myFormId_myFieldName_helpText">myHelpText</small>
                </fieldset>
            ',
            $element->generate()
        );
    }


}
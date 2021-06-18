<?php

namespace FormFactoryTests\Feature\Components\FormControls\Raw;

use FormFactoryTests\TestCase;

class RadioInputTest extends TestCase
{



    public function testSimple()
    {
        $element = \Form::radio('myValue', 'myFieldName');

        $this->assertHtmlEquals(
            '
                <div>
                    <input type="radio" name="myFieldName" value="myValue" id="myFormId_myFieldName_myValue" />
                    <label for="myFormId_myFieldName_myValue"> MyValue </label>
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
                <div>
                    <div id="myFormId_myFieldName_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="radio" name="myFieldName" value="myValue" id="myFormId_myFieldName_myValue" required aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_myValue_helpText" aria-invalid="true" />
                    <label for="myFormId_myFieldName_myValue"> MyValue <sup>*</sup> </label>
                    <small id="myFormId_myFieldName_myValue_helpText">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
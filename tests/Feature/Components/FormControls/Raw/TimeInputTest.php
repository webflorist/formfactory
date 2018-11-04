<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class TimeInputTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::time('myFieldName');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_myFieldName">MyFieldName</label>
                <input type="time" name="myFieldName" id="myFormId_myFieldName" />
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::time('myFieldName')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_myFieldName">MyFieldName<sup>*</sup></label>
                <div id="myFormId_myFieldName_errors">
                    <div>myFirstError</div>
                    <div>mySecondError</div>
                </div>
                <input type="time" name="myFieldName" id="myFormId_myFieldName" required aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" aria-invalid="true" pattern="[a-zA-Z]+" />
                <small id="myFormId_myFieldName_helpText">myHelpText</small>
            ',
            $element->generate()
        );
    }

}
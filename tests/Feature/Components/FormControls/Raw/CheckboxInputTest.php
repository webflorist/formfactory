<?php

namespace FormFactoryTests\Feature\Components\FormControls\Raw;

use FormFactoryTests\TestCase;

class CheckboxInputTest extends TestCase
{



    public function testSimple()
    {
        $element = \Form::checkbox('myFieldName', 'myValue');

        $this->assertHtmlEquals(
            '
                <div>
                    <input type="checkbox" name="myFieldName" value="myValue" id="myFormId_myFieldName" />
                    <label for="myFormId_myFieldName"> MyFieldName </label>
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
                <div>
                    <div id="myFormId_myFieldName_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="checkbox" name="myFieldName" value="myValue" id="myFormId_myFieldName" required aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" aria-invalid="true" />
                    <label for="myFormId_myFieldName"> MyFieldName <sup>*</sup> </label>
                    <small id="myFormId_myFieldName_helpText">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
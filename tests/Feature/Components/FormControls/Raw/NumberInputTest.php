<?php

namespace FormFactoryTests\Feature\Components\FormControls\Raw;

use FormFactoryTests\TestCase;

class NumberInputTest extends TestCase
{



    public function testSimple()
    {
        $element = \Form::number('myFieldName');

        $this->assertHtmlEquals(
            '
                <div>
                    <label for="myFormId_myFieldName">MyFieldName</label>
                    <input type="number" name="myFieldName" id="myFormId_myFieldName" />
                </div>
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::number('myFieldName')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <div>
                    <label for="myFormId_myFieldName">MyFieldName<sup>*</sup></label>
                    <div role="alert" id="myFormId_myFieldName_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="number" name="myFieldName" id="myFormId_myFieldName" required aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" aria-invalid="true" max="10" />
                    <small id="myFormId_myFieldName_helpText">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
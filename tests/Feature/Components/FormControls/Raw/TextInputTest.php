<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class TextInputTest extends TestCase
{

    public function testSimple()
    {
        $element = \Form::text('myFieldName');

        $this->assertHtmlEquals(
            '
                <div>
                    <label for="myFormId_myFieldName">MyFieldName</label>
                    <input type="text" name="myFieldName" id="myFormId_myFieldName" placeholder="MyFieldName" />                
                </div>
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::text('myFieldName')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <div>
                    <label for="myFormId_myFieldName">MyFieldName<sup>*</sup></label>
                    <div id="myFormId_myFieldName_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="text" name="myFieldName" id="myFormId_myFieldName" required pattern="[a-zA-Z]+" maxlength="10" placeholder="MyFieldName" aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" aria-invalid="true" />
                    <small id="myFormId_myFieldName_helpText">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
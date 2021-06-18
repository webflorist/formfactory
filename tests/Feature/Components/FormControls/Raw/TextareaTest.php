<?php

namespace FormFactoryTests\Feature\Components\FormControls\Raw;

use FormFactoryTests\TestCase;

class TextareaTest extends TestCase
{



    public function testSimple()
    {
        $element = \Form::textarea('myFieldName');

        $this->assertHtmlEquals(
            '
                <div>
                    <label for="myFormId_myFieldName">MyFieldName</label>
                    <textarea name="myFieldName" id="myFormId_myFieldName" placeholder="MyFieldName"></textarea>
                </div>
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::textarea('myFieldName')
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
                    <textarea name="myFieldName" id="myFormId_myFieldName" required maxlength="10" placeholder="MyFieldName" aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" aria-invalid="true"></textarea>
                    <small id="myFormId_myFieldName_helpText">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
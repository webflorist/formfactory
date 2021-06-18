<?php

namespace FormFactoryTests\Feature\Components\FormControls\Bootstrap3;

use FormFactoryTests\TestCase;

class TextareaTest extends TestCase
{


    protected $decorators = ['bootstrap:v3'];

    public function testSimple()
    {
        $element = \Form::textarea('myFieldName');

        $this->assertHtmlEquals(
            '
                <div class="form-group">
                    <label for="myFormId_myFieldName">MyFieldName</label>
                    <textarea name="myFieldName" class="form-control" id="myFormId_myFieldName" placeholder="MyFieldName"></textarea>
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
                <div class="form-group has-error">
                    <label for="myFormId_myFieldName">MyFieldName<sup>*</sup></label>
                    <div role="alert" id="myFormId_myFieldName_errors" class="alert m-b-1 alert-danger">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <textarea name="myFieldName" class="form-control" id="myFormId_myFieldName" required maxlength="10" placeholder="MyFieldName" aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" aria-invalid="true"></textarea>
                    <small id="myFormId_myFieldName_helpText" class="text-muted small">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
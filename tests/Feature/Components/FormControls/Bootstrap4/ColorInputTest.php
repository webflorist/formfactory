<?php

namespace FormFactoryTests\Feature\Components\FormControls\Bootstrap4;

use FormFactoryTests\TestCase;

class ColorInputTest extends TestCase
{


    protected $decorators = ['bootstrap:v4'];

    public function testSimple()
    {
        $element = \Form::color('myFieldName');

        $this->assertHtmlEquals(
            '
                <div class="form-group">
                    <label for="myFormId_myFieldName">MyFieldName</label>
                    <input type="color" name="myFieldName" class="form-control" id="myFormId_myFieldName" />
                </div>
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::color('myFieldName')
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
                    <input type="color" name="myFieldName" class="form-control" id="myFormId_myFieldName" required aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" aria-invalid="true" />
                    <small id="myFormId_myFieldName_helpText" class="text-muted form-text small">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
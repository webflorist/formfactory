<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class NumberInputTest extends TestCase
{


    protected $decorators = ['bootstrap:v4'];

    public function testSimple()
    {
        $element = \Form::number('myFieldName');

        $this->assertHtmlEquals(
            '
                <div class="form-group">
                    <label for="myFormId_myFieldName">MyFieldName</label>
                    <input type="number" name="myFieldName" class="form-control" id="myFormId_myFieldName" />
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
                <div class="form-group">
                    <label for="myFormId_myFieldName">MyFieldName<sup>*</sup></label>
                    <div id="myFormId_myFieldName_errors" role="alert" class="invalid-feedback">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="number" name="myFieldName" class="form-control is-invalid" id="myFormId_myFieldName" required aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" aria-invalid="true" max="10" />
                    <small id="myFormId_myFieldName_helpText" class="text-muted form-text small">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
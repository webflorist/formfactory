<?php

namespace FormFactoryTests\Feature\Components\FormControls\Bootstrap3;

use FormFactoryTests\TestCase;

class CheckboxInputTest extends TestCase
{


    protected $decorators = ['bootstrap:v3'];

    public function testSimple()
    {
        $element = \Form::checkbox('myFieldName', 'myValue');

        $this->assertHtmlEquals(
            '
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="myFieldName" value="myValue" id="myFormId_myFieldName" />
                        MyFieldName
                    </label>
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
                <div class="checkbox has-error">
                    <div role="alert" id="myFormId_myFieldName_errors" class="alert m-b-1 alert-danger">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <label>
                        <input type="checkbox" name="myFieldName" value="myValue" id="myFormId_myFieldName" required aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" aria-invalid="true" />
                        MyFieldName<sup>*</sup>
                    </label>
                    <small id="myFormId_myFieldName_helpText" class="text-muted small">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
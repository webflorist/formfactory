<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap3;

use FormFactoryTests\TestCase;

class TextInputTest extends TestCase
{


    protected $decorators = ['bootstrap:v3'];

    public function testSimple()
    {
        $element = \Form::text('myFieldName');

        $this->assertHtmlEquals(
            '
                <div class="form-group">
                    <label for="myFormId_myFieldName">MyFieldName</label>
                    <input type="text" name="myFieldName" class="form-control" id="myFormId_myFieldName" placeholder="MyFieldName" />                
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
                <div class="form-group has-error">
                    <label for="myFormId_myFieldName">MyFieldName<sup>*</sup></label>
                    <div id="myFormId_myFieldName_errors" class="alert alert-danger" role="alert">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="text" name="myFieldName" class="form-control" id="myFormId_myFieldName" required pattern="[a-zA-Z]+" maxlength="10" placeholder="MyFieldName" aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" aria-invalid="true" />
                    <small id="myFormId_myFieldName_helpText" class="text-muted small">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
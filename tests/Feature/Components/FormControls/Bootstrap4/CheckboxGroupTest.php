<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class CheckboxGroupTest extends TestCase
{

    protected $decorators = ['bootstrap:v4'];

    public function testSimple()
    {
        $element = \Form::checkboxGroup([
            \Form::checkbox('myFirstCheckboxFieldName'),
            \Form::checkbox('mySecondCheckboxFieldName'),
        ]);

        $this->assertHtmlEquals(
            '
                <fieldset class="form-group">
                    <div class="form-check">
                        <input type="checkbox" name="myFirstCheckboxFieldName" class="custom-control-input" value="1" id="myFormId_myFirstCheckboxFieldName" />
                        <label class="form-check-label" for="myFormId_myFirstCheckboxFieldName">MyFirstCheckboxFieldName</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="mySecondCheckboxFieldName" class="custom-control-input" value="1" id="myFormId_mySecondCheckboxFieldName" />
                        <label class="form-check-label" for="myFormId_mySecondCheckboxFieldName">MySecondCheckboxFieldName</label>
                    </div>
                </fieldset>
            ',
            $element->generate()
        );
    }


    public function testComplex()
    {
        $element = \Form::checkboxGroup([
            \Form::checkbox('myFirstCheckboxFieldName')
                ->helpText('myFirstCheckboxHelpText')
                ->errors(['myFirstCheckboxFirstError', 'myFirstCheckboxSecondError']),
            \Form::checkbox('mySecondCheckboxFieldName')
                ->helpText('mySecondCheckboxHelpText')
                ->errors(['mySecondCheckboxFirstError', 'mySecondCheckboxSecondError'])
                ->rules('required'),
        ]);

        $this->assertHtmlEquals(
            '
                <fieldset class="form-group">
                    <div role="alert" id="myFormId_myFirstCheckboxFieldName_errors" class="alert m-b-1 alert-danger">
                        <div>myFirstCheckboxFirstError</div>
                        <div>myFirstCheckboxSecondError</div>
                    </div>
                    <div role="alert" id="myFormId_mySecondCheckboxFieldName_errors" class="alert m-b-1 alert-danger">
                        <div>mySecondCheckboxFirstError</div>
                        <div>mySecondCheckboxSecondError</div>
                    </div>
                    <div class="form-check has-error">
                        <input type="checkbox" name="myFirstCheckboxFieldName" class="custom-control-input" value="1" id="myFormId_myFirstCheckboxFieldName" aria-describedby="myFormId_myFirstCheckboxFieldName_errors myFormId_myFirstCheckboxFieldName_helpText" aria-invalid="true" />
                        <label class="form-check-label" for="myFormId_myFirstCheckboxFieldName">MyFirstCheckboxFieldName</label>
                    </div>
                    <div class="form-check has-error">
                        <input type="checkbox" name="mySecondCheckboxFieldName" class="custom-control-input" value="1" id="myFormId_mySecondCheckboxFieldName"  required aria-describedby="myFormId_mySecondCheckboxFieldName_errors myFormId_mySecondCheckboxFieldName_helpText" aria-invalid="true" />
                        <label class="form-check-label" for="myFormId_mySecondCheckboxFieldName">MySecondCheckboxFieldName<sup>*</sup></label>
                    </div>
                    <small id="myFormId_myFirstCheckboxFieldName_helpText" class="text-muted form-text small">myFirstCheckboxHelpText</small>
                    <small id="myFormId_mySecondCheckboxFieldName_helpText" class="text-muted form-text small">mySecondCheckboxHelpText</small>
                </fieldset>
            ',
            $element->generate()
        );
    }


}
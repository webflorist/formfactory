<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap3;

use FormFactoryTests\TestCase;

class CheckboxGroupTest extends TestCase
{

    protected $decorators = ['bootstrap:v3'];

    public function testSimple()
    {
        $element = \Form::checkboxGroup([
            \Form::checkbox('myFirstCheckboxFieldName'),
            \Form::checkbox('mySecondCheckboxFieldName'),
        ]);

        $this->assertHtmlEquals(
            '
                <fieldset class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="myFirstCheckboxFieldName" value="1" id="myFormId_myFirstCheckboxFieldName" />
                            MyFirstCheckboxFieldName
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="mySecondCheckboxFieldName" value="1" id="myFormId_mySecondCheckboxFieldName" />
                            MySecondCheckboxFieldName
                        </label>
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
                    <div class="checkbox has-error">
                        <label>
                            <input type="checkbox" name="myFirstCheckboxFieldName" value="1" id="myFormId_myFirstCheckboxFieldName" aria-describedby="myFormId_myFirstCheckboxFieldName_helpText myFormId_myFirstCheckboxFieldName_errors" aria-invalid="true" />
                            MyFirstCheckboxFieldName
                        </label>
                    </div>
                    <div class="checkbox has-error">
                        <label>
                            <input type="checkbox" name="mySecondCheckboxFieldName" value="1" id="myFormId_mySecondCheckboxFieldName" aria-describedby="myFormId_mySecondCheckboxFieldName_helpText myFormId_mySecondCheckboxFieldName_errors" aria-invalid="true" required />
                            MySecondCheckboxFieldName<sup>*</sup>
                        </label>
                    </div>
                    <small id="myFormId_myFirstCheckboxFieldName_helpText" class="text-muted small">myFirstCheckboxHelpText</small> <small id="myFormId_mySecondCheckboxFieldName_helpText" class="text-muted small">mySecondCheckboxHelpText</small>
                </fieldset>
            ',
            $element->generate()
        );
    }


}
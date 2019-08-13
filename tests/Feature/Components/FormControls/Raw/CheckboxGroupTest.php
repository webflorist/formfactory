<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class CheckboxGroupTest extends TestCase
{

    public function testSimple()
    {
        $element = \Form::checkboxGroup([
            \Form::checkbox('myFirstCheckboxFieldName'),
            \Form::checkbox('mySecondCheckboxFieldName'),
        ]);

        $this->assertHtmlEquals(
            '
                <fieldset>
                    <div>
                        <input type="checkbox" name="myFirstCheckboxFieldName" value="1" id="myFormId_myFirstCheckboxFieldName" />
                        <label for="myFormId_myFirstCheckboxFieldName">MyFirstCheckboxFieldName</label>
                    </div>
                    <div>
                        <input type="checkbox" name="mySecondCheckboxFieldName" value="1" id="myFormId_mySecondCheckboxFieldName" />
                        <label for="myFormId_mySecondCheckboxFieldName">MySecondCheckboxFieldName</label>
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
                <fieldset>
                    <div id="myFormId_myFirstCheckboxFieldName_errors">
                        <div>myFirstCheckboxFirstError</div>
                        <div>myFirstCheckboxSecondError</div>
                    </div>
                    <div id="myFormId_mySecondCheckboxFieldName_errors">
                        <div>mySecondCheckboxFirstError</div>
                        <div>mySecondCheckboxSecondError</div>
                    </div>
                    <div>
                        <input type="checkbox" name="myFirstCheckboxFieldName" value="1" id="myFormId_myFirstCheckboxFieldName" aria-describedby="myFormId_myFirstCheckboxFieldName_errors myFormId_myFirstCheckboxFieldName_helpText" aria-invalid="true" />
                        <label for="myFormId_myFirstCheckboxFieldName">MyFirstCheckboxFieldName</label>
                    </div>
                    <div>
                        <input type="checkbox" name="mySecondCheckboxFieldName" value="1" id="myFormId_mySecondCheckboxFieldName"  required aria-describedby="myFormId_mySecondCheckboxFieldName_errors myFormId_mySecondCheckboxFieldName_helpText" aria-invalid="true" />
                        <label for="myFormId_mySecondCheckboxFieldName">MySecondCheckboxFieldName<sup>*</sup></label>
                    </div>
                    <small id="myFormId_myFirstCheckboxFieldName_helpText">myFirstCheckboxHelpText</small>
                    <small id="myFormId_mySecondCheckboxFieldName_helpText">mySecondCheckboxHelpText</small>
                </fieldset>
            ',
            $element->generate()
        );
    }


}
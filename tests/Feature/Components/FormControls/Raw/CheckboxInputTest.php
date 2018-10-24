<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class CheckboxInputTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::checkbox('checkbox', 'myValue');

        $this->assertHtmlEquals(
            '
                <label>
                    <input type="checkbox" name="checkbox" value="myValue" id="myFormId_checkbox" />
                    Checkbox
                </label>
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::checkbox('checkbox', 'myValue')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <div id="myFormId_checkbox_errors">
                    <div>myFirstError</div>
                    <div>mySecondError</div>
                </div>
                <label>
                    <input type="checkbox" name="checkbox" value="myValue" id="myFormId_checkbox" required aria-describedby="myFormId_checkbox_errors myFormId_checkbox_helpText" aria-invalid="true" />
                    Checkbox<sup>*</sup>
                </label>
                <small id="myFormId_checkbox_helpText">myHelpText</small>
            ',
            $element->generate()
        );
    }

}
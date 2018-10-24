<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class SelectTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::select('select');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_select">Select</label>
                <select name="select" id="myFormId_select"></select>
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::select(
            'select',
            [
                \Form::option('option1'),
                \Form::optgroup('optgroup1',[
                    \Form::option('optgroup1-option1'),
                    \Form::option('optgroup1-option2')
                ]),
                \Form::option('option2'),
                \Form::optgroup('optgroup2',[
                    \Form::option('optgroup2-option1'),
                    \Form::option('optgroup2-option2')
                ]),
            ])
            ->helpText('myHelpText')
            ->errors(['myFirstError','mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_select">Select<sup>*</sup></label>
                <div id="myFormId_select_errors">
                    <div>myFirstError</div>
                    <div>mySecondError</div>
                </div>
                <select name="select" id="myFormId_select" required aria-describedby="myFormId_select_errors myFormId_select_helpText" aria-invalid="true">
                    <option value="option1" id="myFormId_select_option1">option1</option>
                    <optgroup label="optgroup1">
                        <option value="optgroup1-option1" id="myFormId_select_optgroup1-option1">optgroup1-option1</option>
                        <option value="optgroup1-option2" id="myFormId_select_optgroup1-option2">optgroup1-option2</option>
                    </optgroup>
                    <option value="option2" id="myFormId_select_option2">option2</option>
                    <optgroup label="optgroup2">
                        <option value="optgroup2-option1" id="myFormId_select_optgroup2-option1">optgroup2-option1</option>
                        <option value="optgroup2-option2" id="myFormId_select_optgroup2-option2">optgroup2-option2</option>
                    </optgroup>
                </select>
                <small id="myFormId_select_helpText">myHelpText</small>
            ',
            $element->generate()
        );
    }

}
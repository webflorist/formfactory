<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class NumberInputTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::number('number');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_number">Number</label>
                <input type="number" name="number" id="myFormId_number" />
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::number('number')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_number">Number<sup>*</sup></label>
                <div id="myFormId_number_errors">
                    <div>myFirstError</div>
                    <div>mySecondError</div>
                </div>
                <input type="number" name="number" id="myFormId_number" required aria-describedby="myFormId_number_errors myFormId_number_helpText" aria-invalid="true" max="10" />
                <small id="myFormId_number_helpText">myHelpText</small>
            ',
            $element->generate()
        );
    }

}
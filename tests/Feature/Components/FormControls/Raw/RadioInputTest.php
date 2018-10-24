<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class RadioInputTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::radio('myValue', 'radio');

        $this->assertHtmlEquals(
            '
                <label>
                    <input type="radio" name="radio" value="myValue" id="myFormId_radio_myValue" />
                    MyValue
                </label>
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::radio('myValue', 'radio')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <div id="myFormId_radio_myValue_errors">
                    <div>myFirstError</div>
                    <div>mySecondError</div>
                </div>
                <label>
                    <input type="radio" name="radio" value="myValue" id="myFormId_radio_myValue" required aria-describedby="myFormId_radio_myValue_errors myFormId_radio_myValue_helpText" aria-invalid="true" />
                    MyValue<sup>*</sup>
                </label>
                <small id="myFormId_radio_myValue_helpText">myHelpText</small>
            ',
            $element->generate()
        );
    }

}
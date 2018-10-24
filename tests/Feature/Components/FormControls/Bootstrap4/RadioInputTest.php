<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class RadioInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4';
    protected $decorators = ['bootstrap:v4'];

    public function testSimple()
    {
        $element = \Form::radio('myValue', 'radio');

        $this->assertHtmlEquals(
            '
                <div class="form-group form-check">
                    <input type="radio" name="radio" value="myValue" id="myFormId_radio_myValue" class="form-check-input" />
                    <label for="myFormId_radio_myValue">MyValue</label>
                </div>
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
                <div class="form-group form-check has-error">
                    <div id="myFormId_radio_myValue_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="radio" name="radio" value="myValue" id="myFormId_radio_myValue" class="form-check-input" required aria-describedby="myFormId_radio_myValue_errors myFormId_radio_myValue_helpText" aria-invalid="true" />
                    <label for="myFormId_radio_myValue">MyValue<sup>*</sup></label>
                    <small id="myFormId_radio_myValue_helpText">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
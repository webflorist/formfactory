<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class NumberInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4_vue2';
    protected $decorators = ['bootstrap:v4'];
    protected $enableVue = true;

    public function testSimple()
    {
        $element = \Form::number('number');

        $this->assertHtmlEquals(
            '
                <div class="form-group">
                    <label for="myFormId_number">Number</label>
                    <input type="number" name="number" id="myFormId_number" class="form-control" />
                </div>
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
                <div class="form-group has-error">
                    <label for="myFormId_number">Number<sup>*</sup></label>
                    <div id="myFormId_number_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="number" name="number" id="myFormId_number" class="form-control" required max="10" aria-describedby="myFormId_number_errors myFormId_number_helpText" aria-invalid="true" />
                    <small id="myFormId_number_helpText">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class TelInputTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::tel('tel');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_tel">Tel</label>
                <input type="tel" name="tel" id="myFormId_tel" placeholder="Tel" />
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::tel('tel')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_tel">Tel<sup>*</sup></label>
                <div id="myFormId_tel_errors">
                    <div>myFirstError</div>
                    <div>mySecondError</div>
                </div>
                <input type="tel" name="tel" id="myFormId_tel" required aria-describedby="myFormId_tel_errors myFormId_tel_helpText" aria-invalid="true" pattern="[a-zA-Z]+" maxlength="10" placeholder="Tel" />
                <small id="myFormId_tel_helpText">myHelpText</small>
            ',
            $element->generate()
        );
    }

}
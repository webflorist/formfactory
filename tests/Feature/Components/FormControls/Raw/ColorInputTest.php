<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class ColorInputTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::color('color');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_color">Color</label>
                <input type="color" name="color" id="myFormId_color" />
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::color('color')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_color">Color<sup>*</sup></label>
                <div id="myFormId_color_errors">
                    <div>myFirstError</div>
                    <div>mySecondError</div>
                </div>
                <input type="color" name="color" id="myFormId_color" required aria-describedby="myFormId_color_errors myFormId_color_helpText" aria-invalid="true" />
                <small id="myFormId_color_helpText">myHelpText</small>
            ',
            $element->generate()
        );
    }

}
<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class RangeInputTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::range('range');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_range">Range</label>
                <input type="range" name="range" id="myFormId_range" />
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::range('range')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_range">Range<sup>*</sup></label>
                <div id="myFormId_range_errors">
                    <div>myFirstError</div>
                    <div>mySecondError</div>
                </div>
                <input type="range" name="range" id="myFormId_range" required aria-describedby="myFormId_range_errors myFormId_range_helpText" aria-invalid="true" />
                <small id="myFormId_range_helpText">myHelpText</small>
            ',
            $element->generate()
        );
    }

}
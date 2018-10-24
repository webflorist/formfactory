<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class MonthInputTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::month('month');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_month">Month</label>
                <input type="month" name="month" id="myFormId_month" />
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::month('month')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_month">Month<sup>*</sup></label>
                <div id="myFormId_month_errors">
                    <div>myFirstError</div>
                    <div>mySecondError</div>
                </div>
                <input type="month" name="month" id="myFormId_month" required aria-describedby="myFormId_month_errors myFormId_month_helpText" aria-invalid="true" />
                <small id="myFormId_month_helpText">myHelpText</small>
            ',
            $element->generate()
        );
    }

}
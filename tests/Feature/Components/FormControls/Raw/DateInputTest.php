<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class DateInputTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::date('date');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_date">Date</label>
                <input type="date" name="date" id="myFormId_date" />
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::date('date')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_date">Date<sup>*</sup></label>
                <div id="myFormId_date_errors">
                    <div>myFirstError</div>
                    <div>mySecondError</div>
                </div>
                <input type="date" name="date" id="myFormId_date" required aria-describedby="myFormId_date_errors myFormId_date_helpText" aria-invalid="true" pattern="[a-zA-Z]+" />
                <small id="myFormId_date_helpText">myHelpText</small>
            ',
            $element->generate()
        );
    }

}
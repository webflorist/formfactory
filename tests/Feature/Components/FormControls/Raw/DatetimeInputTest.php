<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class DatetimeInputTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::datetime('datetime');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_datetime">Datetime</label>
                <input type="datetime" name="datetime" id="myFormId_datetime" />
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::datetime('datetime')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_datetime">Datetime<sup>*</sup></label>
                <div id="myFormId_datetime_errors">
                    <div>myFirstError</div>
                    <div>mySecondError</div>
                </div>
                <input type="datetime" name="datetime" id="myFormId_datetime" required aria-describedby="myFormId_datetime_errors myFormId_datetime_helpText" aria-invalid="true" />
                <small id="myFormId_datetime_helpText">myHelpText</small>
            ',
            $element->generate()
        );
    }

}
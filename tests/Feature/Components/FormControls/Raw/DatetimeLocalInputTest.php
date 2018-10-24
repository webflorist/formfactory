<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class DatetimeLocalInputTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::datetimeLocal('datetime-local');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_datetime-local">Datetime-local</label>
                <input type="datetime-local" name="datetime-local" id="myFormId_datetime-local" />
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::datetimeLocal('datetime-local')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_datetime-local">Datetime-local<sup>*</sup></label>
                <div id="myFormId_datetime-local_errors">
                    <div>myFirstError</div>
                    <div>mySecondError</div>
                </div>
                <input type="datetime-local" name="datetime-local" id="myFormId_datetime-local" required aria-describedby="myFormId_datetime-local_errors myFormId_datetime-local_helpText" aria-invalid="true" />
                <small id="myFormId_datetime-local_helpText">myHelpText</small>
            ',
            $element->generate()
        );
    }

}
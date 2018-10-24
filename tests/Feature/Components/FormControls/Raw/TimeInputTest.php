<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class TimeInputTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::time('time');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_time">Time</label>
                <input type="time" name="time" id="myFormId_time" />
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::time('time')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_time">Time<sup>*</sup></label>
                <div id="myFormId_time_errors">
                    <div>myFirstError</div>
                    <div>mySecondError</div>
                </div>
                <input type="time" name="time" id="myFormId_time" required aria-describedby="myFormId_time_errors myFormId_time_helpText" aria-invalid="true" pattern="[a-zA-Z]+" />
                <small id="myFormId_time_helpText">myHelpText</small>
            ',
            $element->generate()
        );
    }

}
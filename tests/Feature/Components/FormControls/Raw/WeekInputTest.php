<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class WeekInputTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::week('week');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_week">Week</label>
                <input type="week" name="week" id="myFormId_week" />
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::week('week')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_week">Week<sup>*</sup></label>
                <div id="myFormId_week_errors">
                    <div>myFirstError</div>
                    <div>mySecondError</div>
                </div>
                <input type="week" name="week" id="myFormId_week" required aria-describedby="myFormId_week_errors myFormId_week_helpText" aria-invalid="true" />
                <small id="myFormId_week_helpText">myHelpText</small>
            ',
            $element->generate()
        );
    }

}
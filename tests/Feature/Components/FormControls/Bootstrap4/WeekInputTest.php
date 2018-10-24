<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class WeekInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4';
    protected $decorators = ['bootstrap:v4'];

    public function testSimple()
    {
        $element = \Form::week('week');

        $this->assertHtmlEquals(
            '
                <div class="form-group">
                    <label for="myFormId_week">Week</label>
                    <input type="week" name="week" id="myFormId_week" class="form-control" />
                </div>
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
                <div class="form-group has-error">
                    <label for="myFormId_week">Week<sup>*</sup></label>
                    <div id="myFormId_week_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="week" name="week" id="myFormId_week" class="form-control" required aria-describedby="myFormId_week_errors myFormId_week_helpText" aria-invalid="true" />
                    <small id="myFormId_week_helpText">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
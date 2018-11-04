<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class DatetimeInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4';
    protected $decorators = ['bootstrap:v4'];

    public function testSimple()
    {
        $element = \Form::datetime('datetime');

        $this->assertHtmlEquals(
            '
                <div class="form-group">
                    <label for="myFormId_datetime">Datetime</label>
                    <input type="datetime" name="datetime" id="myFormId_datetime" class="form-control" />
                </div>
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
                <div class="form-group has-error">
                    <label for="myFormId_datetime">Datetime<sup>*</sup></label>
                    <div id="myFormId_datetime_errors" role="alert" class="alert m-b-1 alert-danger">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="datetime" name="datetime" id="myFormId_datetime" class="form-control" required aria-describedby="myFormId_datetime_errors myFormId_datetime_helpText" aria-invalid="true" />
                    <small id="myFormId_datetime_helpText" class="form-text text-muted">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
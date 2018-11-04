<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class MonthInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4';
    protected $decorators = ['bootstrap:v4'];

    public function testSimple()
    {
        $element = \Form::month('month');

        $this->assertHtmlEquals(
            '
                <div class="form-group">
                    <label for="myFormId_month">Month</label>
                    <input type="month" name="month" id="myFormId_month" class="form-control" />
                </div>
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
                <div class="form-group has-error">
                    <label for="myFormId_month">Month<sup>*</sup></label>
                    <div id="myFormId_month_errors" role="alert" class="alert m-b-1 alert-danger">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="month" name="month" id="myFormId_month" class="form-control" required aria-describedby="myFormId_month_errors myFormId_month_helpText" aria-invalid="true" />
                    <small id="myFormId_month_helpText" class="form-text text-muted">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
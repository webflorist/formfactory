<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class DateInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4';
    protected $decorators = ['bootstrap:v4'];

    public function testSimple()
    {
        $element = \Form::date('date');

        $this->assertHtmlEquals(
            '
                <div class="form-group">
                    <label for="myFormId_date">Date</label>
                    <input type="date" name="date" id="myFormId_date" class="form-control" />
                </div>
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
                <div class="form-group has-error">
                    <label for="myFormId_date">Date<sup>*</sup></label>
                    <div id="myFormId_date_errors" role="alert" class="alert m-b-1 alert-danger">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="date" name="date" id="myFormId_date" class="form-control" required pattern="[a-zA-Z]+" aria-describedby="myFormId_date_errors myFormId_date_helpText" aria-invalid="true" />
                    <small id="myFormId_date_helpText" class="form-text text-muted">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
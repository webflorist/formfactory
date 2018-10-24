<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class DatetimeLocalInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4_vue2';
    protected $decorators = ['bootstrap:v4'];
    protected $enableVue = true;

    public function testSimple()
    {
        $element = \Form::datetimeLocal('datetime-local');

        $this->assertHtmlEquals(
            '
                <div class="form-group">
                    <label for="myFormId_datetime-local">Datetime-local</label>
                    <input type="datetime-local" name="datetime-local" id="myFormId_datetime-local" class="form-control" />
                </div>
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
                <div class="form-group has-error">
                    <label for="myFormId_datetime-local">Datetime-local<sup>*</sup></label>
                    <div id="myFormId_datetime-local_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="datetime-local" name="datetime-local" id="myFormId_datetime-local" class="form-control" required aria-describedby="myFormId_datetime-local_errors myFormId_datetime-local_helpText" aria-invalid="true" />
                    <small id="myFormId_datetime-local_helpText">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
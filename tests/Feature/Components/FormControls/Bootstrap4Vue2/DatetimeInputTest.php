<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4Vue2;

use FormFactoryTests\TestCase;

class DatetimeInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4_vue2';
    protected $decorators = ['bootstrap:v4'];
    protected $enableVue = true;

    public function testSimple()
    {
        $element = \Form::datetime('datetime');

        $this->assertHtmlEquals(
            '
                <div class="form-group" v-bind:class="{ \'has-error\': fieldHasError(\'text\') }">
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
                <div class="form-group" v-bind:class="{ \'has-error\': fieldHasError(\'text\') }">
                    <label for="myFormId_datetime">Datetime<sup v-if="fields.week.isRequired">*</sup></label>
                    <div id="myFormId_datetime_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="datetime" name="datetime" id="myFormId_datetime" class="form-control" required aria-describedby="myFormId_datetime_errors myFormId_datetime_helpText" aria-invalid="true" />
                    <small id="myFormId_datetime_helpText">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
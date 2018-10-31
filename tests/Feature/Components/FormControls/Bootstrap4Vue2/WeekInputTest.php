<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4Vue2;

use FormFactoryTests\TestCase;

class WeekInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4_vue2';
    protected $decorators = ['bootstrap:v4'];
    protected $enableVue = true;

    public function testSimple()
    {
        $element = \Form::week('week');

        $this->assertHtmlEquals(
            '
                <div class="form-group" v-bind:class="{ \'has-error\': fieldHasError(\'text\') }">
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
                <div class="form-group" v-bind:class="{ \'has-error\': fieldHasError(\'text\') }">
                    <label for="myFormId_week">Week<sup v-if="fields.week.isRequired">*</sup></label>
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
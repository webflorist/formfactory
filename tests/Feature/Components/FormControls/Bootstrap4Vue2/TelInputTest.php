<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4Vue2;

use FormFactoryTests\TestCase;

class TelInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4_vue2';
    protected $decorators = ['bootstrap:v4'];
    protected $enableVue = true;

    public function testSimple()
    {
        $element = \Form::tel('tel');

        $this->assertHtmlEquals(
            '
                <div class="form-group" v-bind:class="{ \'has-error\': fieldHasError(\'text\') }">
                    <label for="myFormId_tel">Tel</label>
                    <input type="tel" name="tel" id="myFormId_tel" class="form-control" placeholder="Tel" />
                </div>
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::tel('tel')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <div class="form-group" v-bind:class="{ \'has-error\': fieldHasError(\'text\') }">
                    <label for="myFormId_tel">Tel<sup v-if="fields.week.isRequired">*</sup></label>
                    <div id="myFormId_tel_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="tel" name="tel" id="myFormId_tel" class="form-control" required pattern="[a-zA-Z]+" maxlength="10" placeholder="Tel" aria-describedby="myFormId_tel_errors myFormId_tel_helpText" aria-invalid="true" />
                    <small id="myFormId_tel_helpText">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4Vue2;

use FormFactoryTests\TestCase;

class RadioInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4_vue2';
    protected $decorators = ['bootstrap:v4'];
    protected $enableVue = true;

    public function testSimple()
    {
        $element = \Form::radio('myValue', 'radio');

        $this->assertHtmlEquals(
            '
                <div class="form-group form-check" v-bind:class="{ \'has-error\': fieldHasError(\'radio\') }">
                    <div role="alert" class="alert m-b-1 alert-danger" id="myFormId_radio_myValue_errors" v-if="fieldHasError(\'radio\')">
                        <div v-for="error in fields[\'radio\'].errors">{{ error }}</div>
                    </div>
                    <label class="form-check-label" for="myFormId_radio_myValue">MyValue</label>
                    <input type="radio" name="radio" value="myValue" id="myFormId_radio_myValue" class="form-check-input" v-model="fields[\'radio\'].value" v-bind="{ required: fields[\'radio\'].isRequired, disabled: fields[\'radio\'].isDisabled }" />
                </div>
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::radio('myValue', 'radio')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <div class="form-group form-check" v-bind:class="{ \'has-error\': fieldHasError(\'radio\') }">
                    <div role="alert" class="alert m-b-1 alert-danger" id="myFormId_radio_myValue_errors" v-if="fieldHasError(\'radio\')">
                        <div v-for="error in fields[\'radio\'].errors">{{ error }}</div>
                    </div>
                    <label class="form-check-label" for="myFormId_radio_myValue">MyValue<sup v-if="fields[\'radio\'].isRequired">*</sup></label>
                    <input type="radio" name="radio" value="myValue" id="myFormId_radio_myValue" class="form-check-input" required aria-describedby="myFormId_radio_myValue_errors myFormId_radio_myValue_helpText" aria-invalid="true" v-model="fields[\'radio\'].value" v-bind="{ required: fields[\'radio\'].isRequired, disabled: fields[\'radio\'].isDisabled }" />
                    <small id="myFormId_radio_myValue_helpText" class="form-text text-muted">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
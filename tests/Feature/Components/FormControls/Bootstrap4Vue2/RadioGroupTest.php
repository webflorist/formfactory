<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4Vue2;

use FormFactoryTests\TestCase;

class RadioGroupTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4_vue2';
    protected $decorators = ['bootstrap:v4'];
    protected $enableVue = true;

    public function testSimple()
    {
        $element = \Form::radioGroup('radio_group', [
            \Form::radio('myValue1'),
            \Form::radio('myValue2'),
        ])->legend('myLegend');

        $this->assertHtmlEquals(
            '
                <div class="form-group form-check" v-bind:class="{ \'has-error\': fieldHasError(\'radio\') }">
                    <div role="alert" class="alert m-b-1 alert-danger" id="myFormId_radio_myValue_errors" v-if="fieldHasError(\'radio\')">
                        <div v-for="error in fields[\'radio\'].errors">{{ error }}</div>
                    </div>
                    <input type="radio" name="radio" value="myValue" id="myFormId_radio_myValue" class="form-check-input" v-model="fields[\'radio\'].value" v-bind="{ required: fields[\'radio\'].isRequired, disabled: fields[\'radio\'].isDisabled }" />
                    <label class="form-check-label" for="myFormId_radio_myValue">MyValue<sup v-if="fields[\'radio\'].isRequired">*</sup></label>
                </div>
            ',
            $element->generate()
        );
    }

}
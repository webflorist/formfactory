<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4Vue2;

use FormFactoryTests\TestCase;

class CheckboxInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4_vue2';
    protected $decorators = ['bootstrap:v4'];
    protected $enableVue = true;

    public function testSimple()
    {
        $element = \Form::checkbox('checkbox', 'myValue');

        $this->assertHtmlEquals(
            '
                <div class="form-group form-check">
                    <input type="checkbox" name="checkbox" value="myValue" id="myFormId_checkbox" class="form-check-input" v-model="fields.text.value" v-bind="{ required: fields.text.isRequired, disabled: fields.text.isDisabled }" />
                    <label for="myFormId_checkbox">Checkbox</label>
                </div>
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::checkbox('checkbox', 'myValue')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <div class="form-group form-check has-error">
                    <div id="myFormId_checkbox_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="checkbox" name="checkbox" value="myValue" id="myFormId_checkbox" class="form-check-input" required aria-describedby="myFormId_checkbox_errors myFormId_checkbox_helpText" aria-invalid="true" v-model="fields.text.value" v-bind="{ required: fields.text.isRequired, disabled: fields.text.isDisabled }" />
                    <label for="myFormId_checkbox">Checkbox<sup v-if="fields.checkbox.isRequired">*</sup></label>
                    <small id="myFormId_checkbox_helpText">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
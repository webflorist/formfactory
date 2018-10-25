<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4Vue2;

use FormFactoryTests\TestCase;

class TextInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4_vue2';
    protected $decorators = ['bootstrap:v4'];
    protected $enableVue = true;

    public function testSimple()
    {
        $element = \Form::text('text');

        $this->assertHtmlEquals(
            '
                <div class="form-group" v-bind:class="{ \'has-error\': fieldHasError(\'text\') }">
                    <label for="myFormId_text">Text</label>
                    <div role="alert" class="alert m-b-1 alert-danger" id="myFormId_text_errors" v-if="fieldHasError(\'text\')">
                        <div v-for="error in fields[\'text\'].errors"> {{ error }} </div>
                    </div>
                    <input type="text" name="text" id="myFormId_text" class="form-control" placeholder="Text" v-model="fields[\'text\'].value" v-bind="{ required: fields[\'text\'].isRequired, disabled: fields[\'text\'].isDisabled }" />
                </div>
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::text('text')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <div class="form-group" v-bind:class="{ \'has-error\': fieldHasError(\'text\') }">
                    <label for="myFormId_text">Text<sup v-if="fields[\'text\'].isRequired">*</sup></label>
                    <div role="alert" class="alert m-b-1 alert-danger" id="myFormId_text_errors" v-if="fieldHasError(\'text\')">
                        <div v-for="error in fields[\'text\'].errors"> {{ error }} </div>
                    </div>
                    <input type="text" name="text" id="myFormId_text" class="form-control" required pattern="[a-zA-Z]+" maxlength="10" placeholder="Text" aria-describedby="myFormId_text_errors myFormId_text_helpText" aria-invalid="true" v-model="fields[\'text\'].value" v-bind="{ required: fields[\'text\'].isRequired, disabled: fields[\'text\'].isDisabled }" />
                    <small id="myFormId_text_helpText" class="form-text text-muted">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
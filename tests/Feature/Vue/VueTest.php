<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class VueTest extends TestCase
{
    protected $openForm = false;
    protected $closeForm = false;
    protected $decorators = ['bootstrap:v4'];
    protected $enableVue = true;

    public function test_simple_vue_app()
    {
        \Form::open('myFormId');

        $this->assertEquals(
            '<div data-field-wrapper="1" class="form-group" v-bind:class="{ \'has-error\': fields.text.errors.length }"><label for="myFormId_text">Text<sup v-if="fields.text.isRequired">*</sup></label><div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_text_errors" data-displays-errors-for="text" hidden style="display:none"></div><input type="text" name="text" class="form-control" id="myFormId_text" placeholder="Text" v-model="fields.text.value" v-bind="{ required: fields.text.isRequired, disabled: fields.text.isDisabled }" aria-describedby="myFormId_text_errors" /></div>',
            \Form::text('text')->generate()
        );

        \Form::close();

        $vue = \Form::vue('myFormId')->generate();

        $this->assertEquals(
            'new Vue({"el":"#myFormId","data":{"fields":{"text":{"value":"","isRequired":false,"isDisabled":false,"errors":[]}}},"computed":{"hasErrors":function () {return this.fields.text.errors.length > 0;}}});',
            $vue
        );
    }


}
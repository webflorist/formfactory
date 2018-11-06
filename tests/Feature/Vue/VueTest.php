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

        \Form::text('text')->generate();

        \Form::close();

        $vue = \Form::vue('myFormId')->generate();

        $this->assertEquals(
            'new Vue({"el":"#myFormId","data":{"fields":{"text":{"value":"","isRequired":false,"isDisabled":false,"errors":[]}}},"methods":{"fieldHasError":function (fieldName) {return this.fields[fieldName].errors.length > 0;}},"computed":{"hasErrors":function () {return this.fields[\'text\'].errors.length > 0;}}});',
            $vue
        );
    }


}
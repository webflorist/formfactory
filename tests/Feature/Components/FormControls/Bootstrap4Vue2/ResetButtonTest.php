<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4Vue2;

use FormFactoryTests\TestCase;

class ResetButtonTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4_vue2';
    protected $decorators = ['bootstrap:v4'];
    protected $enableVue = true;

    public function testSimple()
    {
        $element = \Form::reset('reset');

        $this->assertHtmlEquals(
            '
                <button type="reset" name="reset" id="myFormId_reset" class="btn btn-secondary">Reset</button>
            ',
            $element->generate()
        );
    }

}
<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4Vue2;

use FormFactoryTests\TestCase;

class HiddenInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4_vue2';
    protected $decorators = ['bootstrap:v4'];
    protected $enableVue = true;

    public function testSimple()
    {
        $element = \Form::hidden('hidden');

        $this->assertHtmlEquals(
            '
                <input type="hidden" name="hidden" id="myFormId_hidden" class="form-control" />
            ',
            $element->generate()
        );
    }

}
<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class HiddenInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4';
    protected $decorators = ['bootstrap:v4'];

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
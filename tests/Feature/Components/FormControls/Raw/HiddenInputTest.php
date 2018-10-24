<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class HiddenInputTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::hidden('hidden');

        $this->assertHtmlEquals(
            '
                <input type="hidden" name="hidden" id="myFormId_hidden" />
            ',
            $element->generate()
        );
    }

}
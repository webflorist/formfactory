<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class ResetButtonTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::reset('reset');

        $this->assertHtmlEquals(
            '
                <button type="reset" name="reset" id="myFormId_reset">Reset</button>
            ',
            $element->generate()
        );
    }

}
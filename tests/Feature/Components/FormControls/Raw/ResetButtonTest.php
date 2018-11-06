<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class ResetButtonTest extends TestCase
{

    protected $viewBase = 'raw';

    public function testSimple()
    {
        $element = \Form::reset('myButtonName');

        $this->assertHtmlEquals(
            '
                <button type="reset" name="myButtonName" id="myFormId_myButtonName">MyButtonName</button>
            ',
            $element->generate()
        );
    }

}
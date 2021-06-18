<?php

namespace FormFactoryTests\Feature\Components\FormControls\Raw;

use FormFactoryTests\TestCase;

class ButtonTest extends TestCase
{



    public function testSimple()
    {
        $element = \Form::button('myButtonName');

        $this->assertHtmlEquals(
            '
                <button type="button" name="myButtonName" id="myFormId_myButtonName">MyButtonName</button>
            ',
            $element->generate()
        );
    }

}
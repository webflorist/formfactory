<?php

namespace FormFactoryTests\Feature\Components\FormControls\Raw;

use FormFactoryTests\TestCase;

class ResetButtonTest extends TestCase
{



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
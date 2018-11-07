<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class SubmitButtonTest extends TestCase
{



    public function testSimple()
    {
        $element = \Form::submit('myButtonName');

        $this->assertHtmlEquals(
            '
                <button type="submit" name="myButtonName" id="myFormId_myButtonName">MyButtonName</button>
            ',
            $element->generate()
        );
    }

}
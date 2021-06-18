<?php

namespace FormFactoryTests\Feature\Components\FormControls\Raw;

use FormFactoryTests\TestCase;

class ButtonGroupTest extends TestCase
{

    public function testSimple()
    {
        $element = \Form::buttonGroup([
            \Form::button('myButtonName'),
            \Form::submit('myButtonName'),
            \Form::reset('myButtonName')
        ]);

        $this->assertHtmlEquals(
            '
                <div role="group">
                    <button type="button" name="myButtonName" id="myFormId_myButtonName">MyButtonName</button>
                    <button type="submit" name="myButtonName" id="myFormId_myButtonName">MyButtonName</button>
                    <button type="reset" name="myButtonName" id="myFormId_myButtonName">MyButtonName</button>
                </div>
            ',
            $element->generate()
        );
    }

}
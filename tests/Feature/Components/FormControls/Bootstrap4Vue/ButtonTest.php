<?php

namespace FormFactoryTests\Feature\Components\FormControls\Bootstrap4Vue;

class ButtonTest extends AbstractBootstrap4VueTest
{

    public function testSimple()
    {
        $element = \Form::button('myButtonName');

        $this->assertHtmlEquals(
            '
                <button type="button" name="myButtonName" class="btn btn-default" id="myFormId_myButtonName">MyButtonName</button>
            ',
            $element->generate()
        );
    }

}
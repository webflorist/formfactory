<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4Vue;

class ResetButtonTest extends AbstractBootstrap4VueTest
{

    public function testSimple()
    {
        $element = \Form::reset('myButtonName');

        $this->assertHtmlEquals(
            '
                <button type="reset" name="myButtonName" class="btn btn-secondary" id="myFormId_myButtonName">MyButtonName</button>
            ',
            $element->generate()
        );
    }

}
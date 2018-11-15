<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4Vue;

class SubmitButtonTest extends AbstractBootstrap4VueTest
{

    public function testSimple()
    {
        $element = \Form::submit('myButtonName');

        $this->assertHtmlEquals(
            '
                <button type="submit" name="myButtonName" class="btn btn-primary" id="myFormId_myButtonName">MyButtonName</button>
            ',
            $element->generate()
        );
    }

}
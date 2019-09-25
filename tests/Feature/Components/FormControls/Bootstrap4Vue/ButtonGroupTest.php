<?php

namespace FormFactoryTests\Feature\Components\FormControls\Bootstrap4Vue;

class ButtonGroupTest extends AbstractBootstrap4VueTest
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
                <div role="group" class="btn-group">
                    <button type="button" name="myButtonName" id="myFormId_myButtonName" class="btn btn-default">MyButtonName</button>
                    <button type="submit" name="myButtonName" id="myFormId_myButtonName" class="btn btn-primary">MyButtonName</button>
                    <button type="reset" name="myButtonName" id="myFormId_myButtonName" class="btn btn-secondary">MyButtonName</button>
                </div>
            ',
            $element->generate()
        );
    }

}
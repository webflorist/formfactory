<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class ResetButtonTest extends TestCase
{


    protected $decorators = ['bootstrap:v4'];

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
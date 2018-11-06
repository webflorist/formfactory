<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class SubmitButtonTest extends TestCase
{

    protected $viewBase = 'raw';
    protected $decorators = ['bootstrap:v4'];

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
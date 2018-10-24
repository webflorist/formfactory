<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class SubmitButtonTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4';
    protected $decorators = ['bootstrap:v4'];

    public function testSimple()
    {
        $element = \Form::submit('submit');

        $this->assertHtmlEquals(
            '
                <button type="submit" name="submit" id="myFormId_submit" class="btn btn-primary">Submit</button>
            ',
            $element->generate()
        );
    }

}
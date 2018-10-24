<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class SubmitButtonTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::submit('submit');

        $this->assertHtmlEquals(
            '
                <button type="submit" name="submit" id="myFormId_submit">Submit</button>
            ',
            $element->generate()
        );
    }

}
<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class TextInputTest extends TestCase
{

    public function testSimpleTextInput()
    {
        $html = \Form::text('text')
            ->generate();

        $this->assertHtmlEquals('<div data-field-wrapper="1"><label for="myFormId_text">Text</label><div role="alert" data-error-container="1" id="myFormId_text_errors" data-displays-errors-for="text" hidden style="display:none"></div><input type="text" name="text" id="myFormId_text" placeholder="Text" aria-describedby="myFormId_text_errors" /></div>',
            $html
        );
    }

    public function testSimpleTextInputComponentForBootstrap3()
    {
        $this->setFrontendFramework('bootstrap', '3');
        $html = \Form::text('text')
            ->generate();

        $this->assertHtmlEquals(
            '<div data-field-wrapper="1" class="form-group"><label for="myFormId_text">Text</label><div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_text_errors" data-displays-errors-for="text" hidden style="display:none"></div><input type="text" name="text" class="form-control" id="myFormId_text" placeholder="Text" aria-describedby="myFormId_text_errors" /></div>',
            $html
        );
    }

    public function testSimpleTextInputComponentForBulma0()
    {
        $this->setFrontendFramework('bulma', '0');
        $html = \Form::text('text')
            ->generate();

        $this->assertHtmlEquals(
            '<div data-field-wrapper="1" class="form-group"><label for="myFormId_text">Text</label><div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_text_errors" data-displays-errors-for="text" hidden style="display:none"></div><input type="text" name="text" class="form-control" id="myFormId_text" placeholder="Text" aria-describedby="myFormId_text_errors" /></div>',
            $html
        );
    }

}
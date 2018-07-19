<?php

namespace FormFactoryTests\Feature\Components\Additional;

use Form;
use FormFactoryTests\TestCase;

class InputGroupTest extends TestCase
{
    public function testInputGroup()
    {

        $html = Form::inputGroup([
            Form::text('input_group_text')->helpText('My Help-Text')->errors(['My First Error', 'My Second Error']),
            Form::text('input_group_text2')->helpText('My Second Help-Text')->errors(['My Third Error', 'My Fourth Error'])
        ])->generate();

        $this->assertHtmlEquals(
            '<div data-field-wrapper="1"><label for="myFormId_input_group_text">Input_group_text</label><div role="alert" data-error-container="1" id="178a7a7435ed25f64bc4a421f6a5f17e_errors" data-displays-errors-for="input_group_text|input_group_text2"><div>My First Error</div><div>My Second Error</div><div>My Third Error</div><div>My Fourth Error</div></div><div><input type="text" name="input_group_text" id="myFormId_input_group_text" placeholder="Input_group_text" aria-invalid="true" aria-describedby="178a7a7435ed25f64bc4a421f6a5f17e_errors 178a7a7435ed25f64bc4a421f6a5f17e_helpText" /><input type="text" name="input_group_text2" id="myFormId_input_group_text2" placeholder="Input_group_text2" aria-invalid="true" aria-describedby="178a7a7435ed25f64bc4a421f6a5f17e_errors 178a7a7435ed25f64bc4a421f6a5f17e_helpText" /></div><div id="178a7a7435ed25f64bc4a421f6a5f17e_helpText">My Help-Text My Second Help-Text</div></div>',
            $html
        );
    }

    public function testInputGroupForBootstrap3()
    {
        $this->setFrontendFramework('bootstrap', '3');
        $html = Form::inputGroup([
            Form::text('input_group_text')->helpText('My Help-Text')->errors(['My First Error', 'My Second Error']),
            Form::text('input_group_text2')->helpText('My Second Help-Text')->errors(['My Third Error', 'My Fourth Error'])
        ])->generate();

        $this->assertHtmlEquals(
            '<div data-field-wrapper="1" class="form-group"><label for="myFormId_input_group_text">Input_group_text</label><div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="178a7a7435ed25f64bc4a421f6a5f17e_errors" data-displays-errors-for="input_group_text|input_group_text2"><div>My First Error</div><div>My Second Error</div><div>My Third Error</div><div>My Fourth Error</div></div><div class="input-group"><input type="text" name="input_group_text" class="form-control" id="myFormId_input_group_text" placeholder="Input_group_text" aria-invalid="true" aria-describedby="178a7a7435ed25f64bc4a421f6a5f17e_errors 178a7a7435ed25f64bc4a421f6a5f17e_helpText" /><input type="text" name="input_group_text2" class="form-control" id="myFormId_input_group_text2" placeholder="Input_group_text2" aria-invalid="true" aria-describedby="178a7a7435ed25f64bc4a421f6a5f17e_errors 178a7a7435ed25f64bc4a421f6a5f17e_helpText" /></div><div class="text-muted small" id="178a7a7435ed25f64bc4a421f6a5f17e_helpText">My Help-Text My Second Help-Text</div></div>',
            $html
        );
    }

    public function testInputGroupForBulma0()
    {
        $this->setFrontendFramework('bulma', '0');
        $html = Form::inputGroup([
            Form::text('input_group_text')->helpText('My Help-Text')->errors(['My First Error', 'My Second Error']),
            Form::text('input_group_text2')->helpText('My Second Help-Text')->errors(['My Third Error', 'My Fourth Error'])
        ])->generate();

        $this->assertHtmlEquals(
            '<div data-field-wrapper="1" class="field"><label class="label" for="myFormId_input_group_text">Input_group_text</label><div role="alert" data-error-container="1" class="notification is-danger" id="178a7a7435ed25f64bc4a421f6a5f17e_errors" data-displays-errors-for="input_group_text|input_group_text2"><div>My First Error</div><div>My Second Error</div><div>My Third Error</div><div>My Fourth Error</div></div><div class="field has-addons"><div class="control"><input type="text" name="input_group_text" class="input" id="myFormId_input_group_text" placeholder="Input_group_text" aria-invalid="true" aria-describedby="178a7a7435ed25f64bc4a421f6a5f17e_errors 178a7a7435ed25f64bc4a421f6a5f17e_helpText" /></div><div class="control"><input type="text" name="input_group_text2" class="input" id="myFormId_input_group_text2" placeholder="Input_group_text2" aria-invalid="true" aria-describedby="178a7a7435ed25f64bc4a421f6a5f17e_errors 178a7a7435ed25f64bc4a421f6a5f17e_helpText" /></div></div><div class="help" id="178a7a7435ed25f64bc4a421f6a5f17e_helpText">My Help-Text My Second Help-Text</div></div>',
            $html
        );
    }
}
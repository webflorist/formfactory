<?php

namespace FormFactoryTests\Feature;

use Form;
use FormFactoryTests\TestCase;

class InputGroupTest extends TestCase
{
    public function testAlert()
    {

        $html = Form::inputGroup([
            Form::text('input_group_text')->helpText('My Help-Text')->errors(['My First Error','My Second Error']),
            Form::text('input_group_text2')->helpText('My Second Help-Text')->errors(['My Third Error','My Fourth Error'])
        ])->generate();

        $this->assertHtmlEquals(
            '<div data-field-wrapper="1"><label for="myFormId_input_group_text">Input_group_text</label><div role="alert" data-error-container="1" id="178a7a7435ed25f64bc4a421f6a5f17e_errors" data-displays-errors-for="input_group_text|input_group_text2"><div>My First Error</div><div>My Second Error</div><div>My Third Error</div><div>My Fourth Error</div></div><div class="input-group"><input type="text" name="input_group_text" id="myFormId_input_group_text" placeholder="Input_group_text" aria-invalid="true" aria-describedby="178a7a7435ed25f64bc4a421f6a5f17e_errors 178a7a7435ed25f64bc4a421f6a5f17e_helpText" /><input type="text" name="input_group_text2" id="myFormId_input_group_text2" placeholder="Input_group_text2" aria-invalid="true" aria-describedby="178a7a7435ed25f64bc4a421f6a5f17e_errors 178a7a7435ed25f64bc4a421f6a5f17e_helpText" /></div><div id="178a7a7435ed25f64bc4a421f6a5f17e_helpText">My Help-Text My Second Help-Text</div></div>',
            $html
        );
    }
}
<?php

namespace FormFactoryTests\Unit;

use Form;
use FormFactoryTests\TestCase;
use Nicat\FormFactory\Utilities\FormFactoryTools;

class RegressionTest extends TestCase
{

    public function test_if_value_set_on_field_element_takes_precedence_over_value_set_via_form_open_call()
    {
        Form::open('myFormId')->values([
            'text' => 'old'
        ])->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1">
    <label for="myFormId_text">Text</label>
    <div role="alert" data-error-container="1" id="myFormId_text_errors" data-displays-errors-for="text" hidden style="display:none"></div>
    <input type="text" name="text" value="new" id="myFormId_text" placeholder="Text" aria-describedby="myFormId_text_errors" />
</div>',
            Form::text('text')->value('new')->generate()
        );
    }

}
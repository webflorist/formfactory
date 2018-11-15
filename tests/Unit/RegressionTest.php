<?php

namespace FormFactoryTests\Unit;

use Form;
use FormFactoryTests\TestCase;
use Webflorist\FormFactory\Utilities\FormFactoryTools;

class RegressionTest extends TestCase
{

    public function test_if_value_set_on_field_element_takes_precedence_over_value_set_via_form_open_call()
    {
        Form::open('myFormId')->values([
            'text' => 'old'
        ])->generate();

        $this->assertHtmlEquals(
            '
                <div>
                    <label for="myFormId_text">Text</label>
                    <input type="text" name="text" id="myFormId_text" value="new" placeholder="Text" />
                </div>
            ',
            Form::text('text')->value('new')->generate()
        );
    }

}
<?php

namespace FormFactoryTests\Feature;

use Form;
use FormFactoryTests\TestCase;

class InputGroupTest extends TestCase
{
    public function testAlert()
    {
        /*
        $html = Form::inputGroup([
            Form::inputGroupAddon('input group addon'),
            Form::text('input_group_text'),
            Form::inputGroupButton(
                Form::button('input group button')
            )
        ])->generate();
        */

        $html = Form::inputGroup([
            Form::text('input_group_text')->helpText('My Help-Text')->errors(['My First Error','My Second Error']),
            Form::text('input_group_text2')->helpText('My Second Help-Text')->errors(['My Third Error','My Fourth Error'])
        ])->generate();

        $this->assertHtmlEquals(
            '<div role="alert">This is an alert!</div>',
            $html
        );
    }
}
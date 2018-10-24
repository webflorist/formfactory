<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class PasswordInputTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::password('password');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_password">Password</label>
                <input type="password" name="password" id="myFormId_password" placeholder="Password" />
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::password('password')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_password">Password<sup>*</sup></label>
                <div id="myFormId_password_errors">
                    <div>myFirstError</div>
                    <div>mySecondError</div>
                </div>
                <input type="password" name="password" id="myFormId_password" required aria-describedby="myFormId_password_errors myFormId_password_helpText" aria-invalid="true" pattern="[a-zA-Z]+" maxlength="10" placeholder="Password" />
                <small id="myFormId_password_helpText">myHelpText</small>
            ',
            $element->generate()
        );
    }

}
<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class PasswordInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4';
    protected $decorators = ['bootstrap:v4'];

    public function testSimple()
    {
        $element = \Form::password('password');

        $this->assertHtmlEquals(
            '
                <div class="form-group">
                    <label for="myFormId_password">Password</label>
                    <input type="password" name="password" id="myFormId_password" class="form-control" placeholder="Password" />
                </div>
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
                <div class="form-group has-error">
                    <label for="myFormId_password">Password<sup>*</sup></label>
                    <div id="myFormId_password_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="password" name="password" id="myFormId_password" class="form-control" required pattern="[a-zA-Z]+" maxlength="10" placeholder="Password" aria-describedby="myFormId_password_errors myFormId_password_helpText" aria-invalid="true" />
                    <small id="myFormId_password_helpText">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
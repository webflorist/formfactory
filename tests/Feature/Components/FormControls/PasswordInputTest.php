<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class PasswordInputTest extends TestCase
{

    public function testSimplePasswordInput()
    {
        $html = \Form::password('password')
            ->generate();

        $this->assertHtmlEquals('
<div data-field-wrapper="1">
    <label for="myFormId_password">Password</label>
    <div role="alert" data-error-container="1" id="myFormId_password_errors" data-displays-errors-for="password" hidden style="display:none"></div>
    <input type="password" name="password" id="myFormId_password" placeholder="Password" aria-describedby="myFormId_password_errors" />
</div>
',
            $html
        );
    }

    public function testSimplePasswordInputComponentForBootstrap3()
    {
        $this->setFrontendFramework('bootstrap', '3');
        $html = \Form::password('password')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_password">Password</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_password_errors" data-displays-errors-for="password" hidden style="display:none"></div>
    <input type="password" name="password" class="form-control" id="myFormId_password" placeholder="Password" aria-describedby="myFormId_password_errors" />
</div>
',
            $html
        );
    }

    public function testSimplePasswordInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $html = \Form::password('password')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_password">Password</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_password_errors" data-displays-errors-for="password" hidden style="display:none"></div>
    <input type="password" name="password" class="form-control" id="myFormId_password" placeholder="Password" aria-describedby="myFormId_password_errors" />
</div>
',
            $html
        );
    }

    public function testComplexPasswordInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $element = \Form::password('password');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group has-error">
    <label for="myId">MyFieldName<sup>*</sup></label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myId_errors" data-displays-errors-for="myFieldName">
        <div>myFirstError</div>
        <div>mySecondError</div>
    </div>
    <input type="password" name="myFieldName" aria-describedby="describedById myId_errors" class="myFirstClass mySecondClass form-control" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" aria-invalid="true" autofocus disabled readonly required value="myValue" pattern="[a-zA-Z]+" maxlength="10" placeholder="MyFieldName" />
</div>
',
            $element->generate()
        );
    }


}
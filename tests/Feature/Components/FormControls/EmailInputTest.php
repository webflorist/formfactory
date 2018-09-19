<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class EmailInputTest extends TestCase
{

    public function testSimpleEmailInput()
    {
        $html = \Form::email('email')
            ->generate();

        $this->assertHtmlEquals('
<div data-field-wrapper="1">
    <label for="myFormId_email">Email</label>
    <div role="alert" data-error-container="1" id="myFormId_email_errors" data-displays-errors-for="email" hidden style="display:none"></div>
    <input type="email" name="email" id="myFormId_email" placeholder="Email" aria-describedby="myFormId_email_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleEmailInputComponentForBootstrap3()
    {
        $this->setFrontendFramework('bootstrap', '3');
        $html = \Form::email('email')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_email">Email</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_email_errors" data-displays-errors-for="email" hidden style="display:none"></div>
    <input type="email" name="email" class="form-control" id="myFormId_email" placeholder="Email" aria-describedby="myFormId_email_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleEmailInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $html = \Form::email('email')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_email">Email</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_email_errors" data-displays-errors-for="email" hidden style="display:none"></div>
    <input type="email" name="email" class="form-control" id="myFormId_email" placeholder="Email" aria-describedby="myFormId_email_errors" />
</div>
',
            $html
        );
    }

    public function testComplexEmailInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $element = \Form::email('email');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group has-error">
    <label for="myId">MyFieldName<sup>*</sup></label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myId_errors" data-displays-errors-for="myFieldName">
        <div>myFirstError</div>
        <div>mySecondError</div>
    </div>
    <input type="email" name="myFieldName" aria-describedby="describedById myId_errors" class="myFirstClass mySecondClass form-control" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" aria-invalid="true" autofocus disabled readonly required value="myValue" pattern="[a-zA-Z]+" maxlength="10" placeholder="MyFieldName" />
</div>
',
            $element->generate()
        );
    }


}
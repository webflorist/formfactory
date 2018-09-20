<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class TelInputTest extends TestCase
{

    public function testSimpleTelInput()
    {
        $html = \Form::tel('tel')
            ->generate();

        $this->assertHtmlEquals('
<div data-field-wrapper="1">
    <label for="myFormId_tel">Tel</label>
    <div role="alert" data-error-container="1" id="myFormId_tel_errors" data-displays-errors-for="tel" hidden style="display:none"></div>
    <input type="tel" name="tel" id="myFormId_tel" placeholder="Tel" aria-describedby="myFormId_tel_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleTelInputComponentForBootstrap3()
    {
        $this->setDecorators(['bootstrap:v3']);
        $html = \Form::tel('tel')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_tel">Tel</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_tel_errors" data-displays-errors-for="tel" hidden style="display:none"></div>
    <input type="tel" name="tel" class="form-control" id="myFormId_tel" placeholder="Tel" aria-describedby="myFormId_tel_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleTelInputComponentForBootstrap4()
    {
        $this->setDecorators(['bootstrap:v4']);
        $html = \Form::tel('tel')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_tel">Tel</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_tel_errors" data-displays-errors-for="tel" hidden style="display:none"></div>
    <input type="tel" name="tel" class="form-control" id="myFormId_tel" placeholder="Tel" aria-describedby="myFormId_tel_errors" />
</div>
',
            $html
        );
    }

    public function testComplexTelInputComponentForBootstrap4()
    {
        $this->setDecorators(['bootstrap:v4']);
        $element = \Form::tel('tel');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group has-error">
    <label for="myId">MyFieldName<sup>*</sup></label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myId_errors" data-displays-errors-for="myFieldName">
        <div>myFirstError</div>
        <div>mySecondError</div>
    </div>
    <input type="tel" name="myFieldName" aria-describedby="describedById myId_errors" class="myFirstClass mySecondClass form-control" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" aria-invalid="true" autofocus disabled readonly required value="myValue" pattern="[a-zA-Z]+" maxlength="10" placeholder="MyFieldName" />
</div>
',
            $element->generate()
        );
    }


}
<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class TextInputTest extends TestCase
{

    public function testSimpleTextInput()
    {
        $html = \Form::text('text')
            ->generate();

        $this->assertHtmlEquals('
<div data-field-wrapper="1">
    <label for="myFormId_text">Text</label>
    <div role="alert" data-error-container="1" id="myFormId_text_errors" data-displays-errors-for="text" hidden style="display:none"></div>
    <input type="text" name="text" id="myFormId_text" placeholder="Text" aria-describedby="myFormId_text_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleTextInputComponentForBootstrap3()
    {
        $this->setFrontendFramework('bootstrap', '3');
        $html = \Form::text('text')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_text">Text</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_text_errors" data-displays-errors-for="text" hidden style="display:none"></div>
    <input type="text" name="text" class="form-control" id="myFormId_text" placeholder="Text" aria-describedby="myFormId_text_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleTextInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $html = \Form::text('text')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_text">Text</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_text_errors" data-displays-errors-for="text" hidden style="display:none"></div>
    <input type="text" name="text" class="form-control" id="myFormId_text" placeholder="Text" aria-describedby="myFormId_text_errors" />
</div>
',
            $html
        );
    }

    public function testComplexTextInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $element = \Form::text('text');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group has-error">
    <label for="myId">MyFieldName<sup>*</sup></label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myId_errors" data-displays-errors-for="myFieldName">
        <div>myFirstError</div>
        <div>mySecondError</div>
    </div>
    <input type="text" name="myFieldName" aria-describedby="describedById myId_errors" class="myFirstClass mySecondClass form-control" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" aria-invalid="true" autofocus disabled readonly required value="myValue" pattern="[a-zA-Z]+" maxlength="10" placeholder="MyFieldName" />
</div>
',
            $element->generate()
        );
    }


}
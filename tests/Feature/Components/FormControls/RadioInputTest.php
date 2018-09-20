<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class RadioInputTest extends TestCase
{

    public function testSimpleRadioInput()
    {
        $html = \Form::radio('1','radio')
            ->generate();

        $this->assertHtmlEquals('
<div data-field-wrapper="1">
    <div role="alert" data-error-container="1" id="myFormId_radio_1_errors" data-displays-errors-for="radio" hidden style="display:none"></div>
    <label>
        <input type="radio" name="radio" value="1" id="myFormId_radio_1" aria-describedby="myFormId_radio_1_errors" />
        1
    </label>
</div>
',
            $html
        );
    }

    public function testSimpleRadioInputComponentForBootstrap3()
    {
        $this->setDecorators(['bootstrap:v3']);
        $html = \Form::radio('1','radio')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="radio">
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_radio_1_errors" data-displays-errors-for="radio" hidden style="display:none"></div>
    <label>
        <input type="radio" name="radio" value="1" id="myFormId_radio_1" aria-describedby="myFormId_radio_1_errors" />
        1
    </label>
</div>
',
            $html
        );
    }

    public function testSimpleRadioInputComponentForBootstrap4()
    {
        $this->setDecorators(['bootstrap:v3']);
        $html = \Form::radio('1','radio')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="radio">
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_radio_1_errors" data-displays-errors-for="radio" hidden style="display:none"></div>
    <label>
        <input type="radio" name="radio" value="1" id="myFormId_radio_1" aria-describedby="myFormId_radio_1_errors" />
        1
    </label>
</div>
',
            $html
        );
    }

    public function testComplexRadioInputComponentForBootstrap4()
    {
        $this->setDecorators(['bootstrap:v4']);
        $element = \Form::radio('1','radio');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-check has-error">
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myId_errors" data-displays-errors-for="myFieldName">
        <div>myFirstError</div>
        <div>mySecondError</div>
    </div>
    <label>
        <input type="radio" name="myFieldName" value="myValue" aria-describedby="describedById myId_errors" class="myFirstClass mySecondClass form-check-input" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" aria-invalid="true" autofocus disabled readonly required />
        MyValue
    </label>
</div>
',
            $element->generate()
        );
    }

}
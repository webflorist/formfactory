<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class TimeInputTest extends TestCase
{

    public function testSimpleTimeInput()
    {
        $html = \Form::time('time')
            ->generate();

        $this->assertHtmlEquals('
<div data-field-wrapper="1">
    <label for="myFormId_time">Time</label>
    <div role="alert" data-error-container="1" id="myFormId_time_errors" data-displays-errors-for="time" hidden style="display:none"></div>
    <input type="time" name="time" id="myFormId_time" aria-describedby="myFormId_time_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleTimeInputComponentForBootstrap3()
    {
        $this->setDecorators(['bootstrap:v3']);
        $html = \Form::time('time')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_time">Time</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_time_errors" data-displays-errors-for="time" hidden style="display:none"></div>
    <input type="time" name="time" class="form-control" id="myFormId_time" aria-describedby="myFormId_time_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleTimeInputComponentForBootstrap4()
    {
        $this->setDecorators(['bootstrap:v4']);
        $html = \Form::time('time')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_time">Time</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_time_errors" data-displays-errors-for="time" hidden style="display:none"></div>
    <input type="time" name="time" class="form-control" id="myFormId_time" aria-describedby="myFormId_time_errors" />
</div>
',
            $html
        );
    }

    public function testComplexTimeInputComponentForBootstrap4()
    {
        $this->setDecorators(['bootstrap:v4']);
        $element = \Form::time('time');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group has-error">
    <label for="myId">MyFieldName<sup>*</sup></label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myId_errors" data-displays-errors-for="myFieldName">
        <div>myFirstError</div>
        <div>mySecondError</div>
    </div>
    <input type="time" name="myFieldName" aria-describedby="describedById myId_errors" class="myFirstClass mySecondClass form-control" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" aria-invalid="true" autofocus disabled readonly required value="myValue" pattern="[a-zA-Z]+" />
</div>
',
            $element->generate()
        );
    }


}
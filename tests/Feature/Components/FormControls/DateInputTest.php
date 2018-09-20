<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class DateInputTest extends TestCase
{

    public function testSimpleDateInput()
    {
        $html = \Form::date('date')
            ->generate();

        $this->assertHtmlEquals('
<div data-field-wrapper="1">
    <label for="myFormId_date">Date</label>
    <div role="alert" data-error-container="1" id="myFormId_date_errors" data-displays-errors-for="date" hidden style="display:none"></div>
    <input type="date" name="date" id="myFormId_date" aria-describedby="myFormId_date_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleDateInputComponentForBootstrap3()
    {
        $this->setDecorators(['bootstrap:v3']);
        $html = \Form::date('date')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_date">Date</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_date_errors" data-displays-errors-for="date" hidden style="display:none"></div>
    <input type="date" name="date" class="form-control" id="myFormId_date" aria-describedby="myFormId_date_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleDateInputComponentForBootstrap4()
    {
        $this->setDecorators(['bootstrap:v4']);
        $html = \Form::date('date')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_date">Date</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_date_errors" data-displays-errors-for="date" hidden style="display:none"></div>
    <input type="date" name="date" class="form-control" id="myFormId_date" aria-describedby="myFormId_date_errors" />
</div>
',
            $html
        );
    }

    public function testComplexDateInputComponentForBootstrap4()
    {
        $this->setDecorators(['bootstrap:v4']);
        $element = \Form::date('date');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group has-error">
    <label for="myId">MyFieldName<sup>*</sup></label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myId_errors" data-displays-errors-for="myFieldName">
        <div>myFirstError</div>
        <div>mySecondError</div>
    </div>
    <input type="date" name="myFieldName" aria-describedby="describedById myId_errors" class="myFirstClass mySecondClass form-control" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" aria-invalid="true" autofocus disabled readonly required value="myValue" pattern="[a-zA-Z]+" />
</div>
',
            $element->generate()
        );
    }


}
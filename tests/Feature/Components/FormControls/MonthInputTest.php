<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class MonthInputTest extends TestCase
{

    public function testSimpleMonthInput()
    {
        $html = \Form::month('month')
            ->generate();

        $this->assertHtmlEquals('
<div data-field-wrapper="1">
    <label for="myFormId_month">Month</label>
    <div role="alert" data-error-container="1" id="myFormId_month_errors" data-displays-errors-for="month" hidden style="display:none"></div>
    <input type="month" name="month" id="myFormId_month" aria-describedby="myFormId_month_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleMonthInputComponentForBootstrap3()
    {
        $this->setDecorators(['bootstrap:v3']);
        $html = \Form::month('month')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_month">Month</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_month_errors" data-displays-errors-for="month" hidden style="display:none"></div>
    <input type="month" name="month" class="form-control" id="myFormId_month" aria-describedby="myFormId_month_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleMonthInputComponentForBootstrap4()
    {
        $this->setDecorators(['bootstrap:v4']);
        $html = \Form::month('month')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_month">Month</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_month_errors" data-displays-errors-for="month" hidden style="display:none"></div>
    <input type="month" name="month" class="form-control" id="myFormId_month" aria-describedby="myFormId_month_errors" />
</div>
',
            $html
        );
    }

    public function testComplexMonthInputComponentForBootstrap4()
    {
        $this->setDecorators(['bootstrap:v4']);
        $element = \Form::month('month');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group has-error">
    <label for="myId">MyFieldName<sup>*</sup></label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myId_errors" data-displays-errors-for="myFieldName">
        <div>myFirstError</div>
        <div>mySecondError</div>
    </div>
    <input type="month" name="myFieldName" aria-describedby="describedById myId_errors" class="myFirstClass mySecondClass form-control" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" aria-invalid="true" autofocus disabled readonly required value="myValue" />
</div>
',
            $element->generate()
        );
    }


}
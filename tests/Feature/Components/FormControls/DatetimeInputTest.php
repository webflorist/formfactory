<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class DatetimeInputTest extends TestCase
{

    public function testSimpleDatetimeInput()
    {
        $html = \Form::datetime('datetime')
            ->generate();

        $this->assertHtmlEquals('
<div data-field-wrapper="1">
    <label for="myFormId_datetime">Datetime</label>
    <div role="alert" data-error-container="1" id="myFormId_datetime_errors" data-displays-errors-for="datetime" hidden style="display:none"></div>
    <input type="datetime" name="datetime" id="myFormId_datetime" aria-describedby="myFormId_datetime_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleDatetimeInputComponentForBootstrap3()
    {
        $this->setFrontendFramework('bootstrap', '3');
        $html = \Form::datetime('datetime')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_datetime">Datetime</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_datetime_errors" data-displays-errors-for="datetime" hidden style="display:none"></div>
    <input type="datetime" name="datetime" class="form-control" id="myFormId_datetime" aria-describedby="myFormId_datetime_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleDatetimeInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $html = \Form::datetime('datetime')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_datetime">Datetime</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_datetime_errors" data-displays-errors-for="datetime" hidden style="display:none"></div>
    <input type="datetime" name="datetime" class="form-control" id="myFormId_datetime" aria-describedby="myFormId_datetime_errors" />
</div>
',
            $html
        );
    }

    public function testComplexDatetimeInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $element = \Form::datetime('datetime');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group has-error">
    <label for="myId">MyFieldName<sup>*</sup></label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myId_errors" data-displays-errors-for="myFieldName">
        <div>myFirstError</div>
        <div>mySecondError</div>
    </div>
    <input type="datetime" name="myFieldName" aria-describedby="describedById myId_errors" class="myFirstClass mySecondClass form-control" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" aria-invalid="true" autofocus disabled readonly required value="myValue" />
</div>
',
            $element->generate()
        );
    }


}
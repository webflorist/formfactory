<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class DatetimeLocalInputTest extends TestCase
{

    public function testSimpleDatetimeLocalInput()
    {
        $html = \Form::datetimeLocal('datetimeLocal')
            ->generate();

        $this->assertHtmlEquals('
<div data-field-wrapper="1">
    <label for="myFormId_datetimeLocal">DatetimeLocal</label>
    <div role="alert" data-error-container="1" id="myFormId_datetimeLocal_errors" data-displays-errors-for="datetimeLocal" hidden style="display:none"></div>
    <input type="datetime-local" name="datetimeLocal" id="myFormId_datetimeLocal" aria-describedby="myFormId_datetimeLocal_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleDatetimeLocalInputComponentForBootstrap3()
    {
        $this->setFrontendFramework('bootstrap', '3');
        $html = \Form::datetimeLocal('datetimeLocal')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_datetimeLocal">DatetimeLocal</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_datetimeLocal_errors" data-displays-errors-for="datetimeLocal" hidden style="display:none"></div>
    <input type="datetime-local" name="datetimeLocal" class="form-control" id="myFormId_datetimeLocal" aria-describedby="myFormId_datetimeLocal_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleDatetimeLocalInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $html = \Form::datetimeLocal('datetimeLocal')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_datetimeLocal">DatetimeLocal</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_datetimeLocal_errors" data-displays-errors-for="datetimeLocal" hidden style="display:none"></div>
    <input type="datetime-local" name="datetimeLocal" class="form-control" id="myFormId_datetimeLocal" aria-describedby="myFormId_datetimeLocal_errors" />
</div>
',
            $html
        );
    }

    public function testComplexDatetimeLocalInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $element = \Form::datetimeLocal('datetimeLocal');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group has-error">
    <label for="myId">MyFieldName<sup>*</sup></label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myId_errors" data-displays-errors-for="myFieldName">
        <div>myFirstError</div>
        <div>mySecondError</div>
    </div>
    <input type="datetime-local" name="myFieldName" aria-describedby="describedById myId_errors" class="myFirstClass mySecondClass form-control" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" aria-invalid="true" autofocus disabled readonly required value="myValue" />
</div>
',
            $element->generate()
        );
    }


}
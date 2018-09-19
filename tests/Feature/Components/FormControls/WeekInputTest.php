<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class WeekInputTest extends TestCase
{

    public function testSimpleWeekInput()
    {
        $html = \Form::week('week')
            ->generate();

        $this->assertHtmlEquals('
<div data-field-wrapper="1">
    <label for="myFormId_week">Week</label>
    <div role="alert" data-error-container="1" id="myFormId_week_errors" data-displays-errors-for="week" hidden style="display:none"></div>
    <input type="week" name="week" id="myFormId_week" aria-describedby="myFormId_week_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleWeekInputComponentForBootstrap3()
    {
        $this->setFrontendFramework('bootstrap', '3');
        $html = \Form::week('week')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_week">Week</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_week_errors" data-displays-errors-for="week" hidden style="display:none"></div>
    <input type="week" name="week" class="form-control" id="myFormId_week" aria-describedby="myFormId_week_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleWeekInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $html = \Form::week('week')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_week">Week</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_week_errors" data-displays-errors-for="week" hidden style="display:none"></div>
    <input type="week" name="week" class="form-control" id="myFormId_week" aria-describedby="myFormId_week_errors" />
</div>
',
            $html
        );
    }

    public function testComplexWeekInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $element = \Form::week('week');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group has-error">
    <label for="myId">MyFieldName<sup>*</sup></label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myId_errors" data-displays-errors-for="myFieldName">
        <div>myFirstError</div>
        <div>mySecondError</div>
    </div>
    <input type="week" name="myFieldName" aria-describedby="describedById myId_errors" class="myFirstClass mySecondClass form-control" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" aria-invalid="true" autofocus disabled readonly required value="myValue" />
</div>
',
            $element->generate()
        );
    }


}
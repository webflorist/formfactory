<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class CheckboxInputTest extends TestCase
{

    public function testSimpleCheckboxInput()
    {
        $html = \Form::checkbox('checkbox')
            ->generate();

        $this->assertHtmlEquals('
<div data-field-wrapper="1">
    <div role="alert" data-error-container="1" id="myFormId_checkbox_errors" data-displays-errors-for="checkbox" hidden style="display:none"></div>
    <label>
        <input type="checkbox" name="checkbox" value="1" id="myFormId_checkbox" aria-describedby="myFormId_checkbox_errors" />
        Checkbox
    </label>
</div>
',
            $html
        );
    }

    public function testSimpleCheckboxInputComponentForBootstrap3()
    {
        $this->setFrontendFramework('bootstrap', '3');
        $html = \Form::checkbox('checkbox')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="checkbox">
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_checkbox_errors" data-displays-errors-for="checkbox" hidden style="display:none"></div>
    <label>
        <input type="checkbox" name="checkbox" value="1" id="myFormId_checkbox" aria-describedby="myFormId_checkbox_errors" />
        Checkbox
    </label>
</div>
',
            $html
        );
    }

    public function testSimpleCheckboxInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '3');
        $html = \Form::checkbox('checkbox')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="checkbox">
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_checkbox_errors" data-displays-errors-for="checkbox" hidden style="display:none"></div>
    <label>
        <input type="checkbox" name="checkbox" value="1" id="myFormId_checkbox" aria-describedby="myFormId_checkbox_errors" />
        Checkbox
    </label>
</div>
',
            $html
        );
    }

    public function testComplexCheckboxInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $element = \Form::checkbox('checkbox');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-check has-error">
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myId_errors" data-displays-errors-for="myFieldName">
        <div>myFirstError</div>
        <div>mySecondError</div>
    </div>
    <label>
        <input type="checkbox" name="myFieldName" value="myValue" aria-describedby="describedById myId_errors" class="myFirstClass mySecondClass form-check-input" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" aria-invalid="true" autofocus disabled readonly required />
        MyFieldName<sup>*</sup>
    </label>
</div>
',
            $element->generate()
        );
    }

}
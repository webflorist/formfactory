<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class NumberInputTest extends TestCase
{

    public function testSimpleNumberInput()
    {
        $html = \Form::number('number')
            ->generate();

        $this->assertHtmlEquals('
<div data-field-wrapper="1">
    <label for="myFormId_number">Number</label>
    <div role="alert" data-error-container="1" id="myFormId_number_errors" data-displays-errors-for="number" hidden style="display:none"></div>
    <input type="number" name="number" id="myFormId_number" aria-describedby="myFormId_number_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleNumberInputComponentForBootstrap3()
    {
        $this->setFrontendFramework('bootstrap', '3');
        $html = \Form::number('number')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_number">Number</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_number_errors" data-displays-errors-for="number" hidden style="display:none"></div>
    <input type="number" name="number" class="form-control" id="myFormId_number" aria-describedby="myFormId_number_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleNumberInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $html = \Form::number('number')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_number">Number</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_number_errors" data-displays-errors-for="number" hidden style="display:none"></div>
    <input type="number" name="number" class="form-control" id="myFormId_number" aria-describedby="myFormId_number_errors" />
</div>
',
            $html
        );
    }

    public function testComplexNumberInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $element = \Form::number('number');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group has-error">
    <label for="myId">MyFieldName<sup>*</sup></label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myId_errors" data-displays-errors-for="myFieldName">
        <div>myFirstError</div>
        <div>mySecondError</div>
    </div>
    <input type="number" name="myFieldName" aria-describedby="describedById myId_errors" class="myFirstClass mySecondClass form-control" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" aria-invalid="true" autofocus disabled readonly required value="myValue" max="10" />
</div>
',
            $element->generate()
        );
    }


}
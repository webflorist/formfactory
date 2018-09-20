<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class RangeInputTest extends TestCase
{

    public function testSimpleRangeInput()
    {
        $html = \Form::range('range')
            ->generate();

        $this->assertHtmlEquals('
<div data-field-wrapper="1">
    <label for="myFormId_range">Range</label>
    <div role="alert" data-error-container="1" id="myFormId_range_errors" data-displays-errors-for="range" hidden style="display:none"></div>
    <input type="range" name="range" id="myFormId_range" aria-describedby="myFormId_range_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleRangeInputComponentForBootstrap3()
    {
        $this->setDecorators(['bootstrap:v3']);
        $html = \Form::range('range')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_range">Range</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_range_errors" data-displays-errors-for="range" hidden style="display:none"></div>
    <input type="range" name="range" class="form-control" id="myFormId_range" aria-describedby="myFormId_range_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleRangeInputComponentForBootstrap4()
    {
        $this->setDecorators(['bootstrap:v4']);
        $html = \Form::range('range')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_range">Range</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_range_errors" data-displays-errors-for="range" hidden style="display:none"></div>
    <input type="range" name="range" class="form-control" id="myFormId_range" aria-describedby="myFormId_range_errors" />
</div>
',
            $html
        );
    }

    public function testComplexRangeInputComponentForBootstrap4()
    {
        $this->setDecorators(['bootstrap:v4']);
        $element = \Form::range('range');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group has-error">
    <label for="myId">MyFieldName<sup>*</sup></label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myId_errors" data-displays-errors-for="myFieldName">
        <div>myFirstError</div>
        <div>mySecondError</div>
    </div>
    <input type="range" name="myFieldName" aria-describedby="describedById myId_errors" class="myFirstClass mySecondClass form-control" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" aria-invalid="true" autofocus disabled readonly required value="myValue" />
</div>
',
            $element->generate()
        );
    }


}
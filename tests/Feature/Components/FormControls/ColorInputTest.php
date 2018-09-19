<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class ColorInputTest extends TestCase
{

    public function testSimpleColorInput()
    {
        $html = \Form::color('color')
            ->generate();

        $this->assertHtmlEquals('
<div data-field-wrapper="1">
    <label for="myFormId_color">Color</label>
    <div role="alert" data-error-container="1" id="myFormId_color_errors" data-displays-errors-for="color" hidden style="display:none"></div>
    <input type="color" name="color" id="myFormId_color" aria-describedby="myFormId_color_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleColorInputComponentForBootstrap3()
    {
        $this->setFrontendFramework('bootstrap', '3');
        $html = \Form::color('color')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_color">Color</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_color_errors" data-displays-errors-for="color" hidden style="display:none"></div>
    <input type="color" name="color" class="form-control" id="myFormId_color" aria-describedby="myFormId_color_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleColorInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $html = \Form::color('color')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_color">Color</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_color_errors" data-displays-errors-for="color" hidden style="display:none"></div>
    <input type="color" name="color" class="form-control" id="myFormId_color" aria-describedby="myFormId_color_errors" />
</div>
',
            $html
        );
    }

    public function testComplexColorInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $element = \Form::color('color');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group has-error">
    <label for="myId">MyFieldName<sup>*</sup></label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myId_errors" data-displays-errors-for="myFieldName">
        <div>myFirstError</div>
        <div>mySecondError</div>
    </div>
    <input type="color" name="myFieldName" aria-describedby="describedById myId_errors" class="myFirstClass mySecondClass form-control" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" aria-invalid="true" autofocus disabled readonly required value="myValue" />
</div>
',
            $element->generate()
        );
    }


}
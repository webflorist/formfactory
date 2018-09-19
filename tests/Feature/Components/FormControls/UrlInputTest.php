<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class UrlInputTest extends TestCase
{

    public function testSimpleUrlInput()
    {
        $html = \Form::url('url')
            ->generate();

        $this->assertHtmlEquals('
<div data-field-wrapper="1">
    <label for="myFormId_url">Url</label>
    <div role="alert" data-error-container="1" id="myFormId_url_errors" data-displays-errors-for="url" hidden style="display:none"></div>
    <input type="url" name="url" id="myFormId_url" placeholder="Url" aria-describedby="myFormId_url_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleUrlInputComponentForBootstrap3()
    {
        $this->setFrontendFramework('bootstrap', '3');
        $html = \Form::url('url')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_url">Url</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_url_errors" data-displays-errors-for="url" hidden style="display:none"></div>
    <input type="url" name="url" class="form-control" id="myFormId_url" placeholder="Url" aria-describedby="myFormId_url_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleUrlInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $html = \Form::url('url')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_url">Url</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_url_errors" data-displays-errors-for="url" hidden style="display:none"></div>
    <input type="url" name="url" class="form-control" id="myFormId_url" placeholder="Url" aria-describedby="myFormId_url_errors" />
</div>
',
            $html
        );
    }

    public function testComplexUrlInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $element = \Form::url('url');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group has-error">
    <label for="myId">MyFieldName<sup>*</sup></label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myId_errors" data-displays-errors-for="myFieldName">
        <div>myFirstError</div>
        <div>mySecondError</div>
    </div>
    <input type="url" name="myFieldName" aria-describedby="describedById myId_errors" class="myFirstClass mySecondClass form-control" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" aria-invalid="true" autofocus disabled readonly required value="myValue" pattern="[a-zA-Z]+" maxlength="10" placeholder="MyFieldName" />
</div>
',
            $element->generate()
        );
    }


}
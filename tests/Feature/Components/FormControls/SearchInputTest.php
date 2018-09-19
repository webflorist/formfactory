<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class SearchInputTest extends TestCase
{

    public function testSimpleSearchInput()
    {
        $html = \Form::search('search')
            ->generate();

        $this->assertHtmlEquals('
<div data-field-wrapper="1">
    <label for="myFormId_search">Search</label>
    <div role="alert" data-error-container="1" id="myFormId_search_errors" data-displays-errors-for="search" hidden style="display:none"></div>
    <input type="search" name="search" id="myFormId_search" placeholder="Search" aria-describedby="myFormId_search_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleSearchInputComponentForBootstrap3()
    {
        $this->setFrontendFramework('bootstrap', '3');
        $html = \Form::search('search')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_search">Search</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_search_errors" data-displays-errors-for="search" hidden style="display:none"></div>
    <input type="search" name="search" class="form-control" id="myFormId_search" placeholder="Search" aria-describedby="myFormId_search_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleSearchInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $html = \Form::search('search')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_search">Search</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_search_errors" data-displays-errors-for="search" hidden style="display:none"></div>
    <input type="search" name="search" class="form-control" id="myFormId_search" placeholder="Search" aria-describedby="myFormId_search_errors" />
</div>
',
            $html
        );
    }

    public function testComplexSearchInputComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $element = \Form::search('search');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group has-error">
    <label for="myId">MyFieldName<sup>*</sup></label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myId_errors" data-displays-errors-for="myFieldName">
        <div>myFirstError</div>
        <div>mySecondError</div>
    </div>
    <input type="search" name="myFieldName" aria-describedby="describedById myId_errors" class="myFirstClass mySecondClass form-control" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" aria-invalid="true" autofocus disabled readonly required value="myValue" pattern="[a-zA-Z]+" maxlength="10" placeholder="MyFieldName" />
</div>
',
            $element->generate()
        );
    }


}
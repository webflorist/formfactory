<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class SelectTest extends TestCase
{

    public function testSimpleSelect()
    {
        $html = \Form::select('select')
            ->generate();

        $this->assertHtmlEquals('
<div data-field-wrapper="1">
    <label for="myFormId_select">Select</label>
    <div role="alert" data-error-container="1" id="myFormId_select_errors" data-displays-errors-for="select" hidden style="display:none"></div>
    <select name="select" id="myFormId_select" aria-describedby="myFormId_select_errors"></select>
</div>
',
            $html
        );
    }

    public function testComplexSelectComponent()
    {
        $element = \Form::select('select', [
            \Form::option('option1'),
            \Form::optgroup('optgroup1',[
                \Form::option('optgroup1-option1'),
                \Form::option('optgroup1-option2')
            ]),
            \Form::option('option2'),
            \Form::optgroup('optgroup2',[
                \Form::option('optgroup2-option1'),
                \Form::option('optgroup2-option2')
            ]),
        ]);

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1">
    <label for="myId">Select<sup>*</sup></label>
    <div role="alert" data-error-container="1" id="myId_errors" data-displays-errors-for="select">
        <div>myFirstError</div>
        <div>mySecondError</div>
    </div>
    <select name="select" aria-describedby="describedById myId_errors" class="myFirstClass mySecondClass" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" required aria-invalid="true">
        <option value="option1" id="myFormId_select_option1">option1</option>
        <optgroup label="optgroup1">
            <option value="optgroup1-option1" id="myFormId_select_optgroup1-option1">optgroup1-option1</option>
            <option value="optgroup1-option2" id="myFormId_select_optgroup1-option2">optgroup1-option2</option>
        </optgroup>
        <option value="option2" id="myFormId_select_option2">option2</option>
        <optgroup label="optgroup2">
            <option value="optgroup2-option1" id="myFormId_select_optgroup2-option1">optgroup2-option1</option>
            <option value="optgroup2-option2" id="myFormId_select_optgroup2-option2">optgroup2-option2</option>
        </optgroup>
    </select>
</div>
',
            $element->generate()
        );
    }


    public function testSimpleSelectComponentForBootstrap3()
    {
        $this->setFrontendFramework('bootstrap', '3');
        $html = \Form::select('select')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_select">Select</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_select_errors" data-displays-errors-for="select" hidden style="display:none"></div>
    <select name="select" class="form-control" id="myFormId_select" aria-describedby="myFormId_select_errors"></select>
</div>
',
            $html
        );
    }

    public function testSimpleSelectComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $html = \Form::select('select')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_select">Select</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_select_errors" data-displays-errors-for="select" hidden style="display:none"></div>
    <select name="select" class="form-control" id="myFormId_select" aria-describedby="myFormId_select_errors"></select>
</div>
',
            $html
        );
    }

    public function testComplexSelectComponentForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $element = \Form::select('select', [
            \Form::option('option1'),
            \Form::optgroup('optgroup1',[
                \Form::option('optgroup1-option1'),
                \Form::option('optgroup1-option2')
            ]),
            \Form::option('option2'),
            \Form::optgroup('optgroup2',[
                \Form::option('optgroup2-option1'),
                \Form::option('optgroup2-option2')
            ]),
        ]);

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group has-error">
    <label for="myId">Select<sup>*</sup></label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myId_errors" data-displays-errors-for="select">
        <div>myFirstError</div>
        <div>mySecondError</div>
    </div>
    <select name="select" aria-describedby="describedById myId_errors" class="myFirstClass mySecondClass form-control" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" required aria-invalid="true">
        <option value="option1" id="myFormId_select_option1">option1</option>
        <optgroup label="optgroup1">
            <option value="optgroup1-option1" id="myFormId_select_optgroup1-option1">optgroup1-option1</option>
            <option value="optgroup1-option2" id="myFormId_select_optgroup1-option2">optgroup1-option2</option>
        </optgroup>
        <option value="option2" id="myFormId_select_option2">option2</option>
        <optgroup label="optgroup2">
            <option value="optgroup2-option1" id="myFormId_select_optgroup2-option1">optgroup2-option1</option>
            <option value="optgroup2-option2" id="myFormId_select_optgroup2-option2">optgroup2-option2</option>
        </optgroup>
    </select>
</div>
',
            $element->generate()
        );
    }

}
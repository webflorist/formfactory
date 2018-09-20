<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class FileInputTest extends TestCase
{

    public function testSimpleFileInput()
    {
        $html = \Form::file('file')
            ->generate();

        $this->assertHtmlEquals('
<div data-field-wrapper="1">
    <label for="myFormId_file">File</label>
    <div role="alert" data-error-container="1" id="myFormId_file_errors" data-displays-errors-for="file" hidden style="display:none"></div>
    <input type="file" name="file" id="myFormId_file" aria-describedby="myFormId_file_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleFileInputComponentForBootstrap3()
    {
        $this->setDecorators(['bootstrap:v3']);
        $html = \Form::file('file')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_file">File</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_file_errors" data-displays-errors-for="file" hidden style="display:none"></div>
    <input type="file" name="file" class="form-control-file" id="myFormId_file" aria-describedby="myFormId_file_errors" />
</div>
',
            $html
        );
    }

    public function testSimpleFileInputComponentForBootstrap4()
    {
        $this->setDecorators(['bootstrap:v4']);
        $html = \Form::file('file')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_file">File</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_file_errors" data-displays-errors-for="file" hidden style="display:none"></div>
    <input type="file" name="file" class="form-control-file" id="myFormId_file" aria-describedby="myFormId_file_errors" />
</div>
',
            $html
        );
    }

    public function testComplexFileInputComponentForBootstrap4()
    {
        $this->setDecorators(['bootstrap:v4']);
        $element = \Form::file('file');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group has-error">
    <label for="myId">MyFieldName<sup>*</sup></label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myId_errors" data-displays-errors-for="myFieldName">
        <div>myFirstError</div>
        <div>mySecondError</div>
    </div>
    <input type="file" name="myFieldName" aria-describedby="describedById myId_errors" class="myFirstClass mySecondClass form-control-file" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" aria-invalid="true" autofocus disabled readonly required value="myValue" />
</div>
',
            $element->generate()
        );
    }


}
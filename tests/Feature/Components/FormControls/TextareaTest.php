<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class TextareaTest extends TestCase
{

    public function testSimpleTextarea()
    {
        $html = \Form::textarea('textarea')
            ->generate();

        $this->assertHtmlEquals('
<div data-field-wrapper="1">
    <label for="myFormId_textarea">Textarea</label>
    <div role="alert" data-error-container="1" id="myFormId_textarea_errors" data-displays-errors-for="textarea" hidden style="display:none"></div>
    <textarea name="textarea" id="myFormId_textarea" placeholder="Textarea" aria-describedby="myFormId_textarea_errors"></textarea>
</div>
',
            $html
        );
    }

    public function testSimpleTextareaForBootstrap3()
    {
        $this->setFrontendFramework('bootstrap', '3');
        $html = \Form::textarea('textarea')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_textarea">Textarea</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_textarea_errors" data-displays-errors-for="textarea" hidden style="display:none"></div>
    <textarea name="textarea" class="form-control" id="myFormId_textarea" placeholder="Textarea" aria-describedby="myFormId_textarea_errors"></textarea>
</div>
',
            $html
        );
    }

    public function testSimpleTextareaForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $html = \Form::textarea('textarea')
            ->generate();

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group">
    <label for="myFormId_textarea">Textarea</label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myFormId_textarea_errors" data-displays-errors-for="textarea" hidden style="display:none"></div>
    <textarea name="textarea" class="form-control" id="myFormId_textarea" placeholder="Textarea" aria-describedby="myFormId_textarea_errors"></textarea>
</div>
',
            $html
        );
    }

    public function testComplexTextareaForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $element = \Form::textarea('textarea');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<div data-field-wrapper="1" class="form-group has-error">
    <label for="myId">Textarea<sup>*</sup></label>
    <div role="alert" data-error-container="1" class="alert m-b-1 alert-danger" id="myId_errors" data-displays-errors-for="textarea">
        <div>myFirstError</div>
        <div>mySecondError</div>
    </div>
    <textarea name="textarea" aria-describedby="describedById myId_errors" class="myFirstClass mySecondClass form-control" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" required maxlength="10" placeholder="Textarea" aria-invalid="true"></textarea>
</div>
',
            $element->generate()
        );
    }


}
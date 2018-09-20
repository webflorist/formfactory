<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class HiddenInputTest extends TestCase
{

    public function testSimpleHiddenInput()
    {
        $html = \Form::hidden('hidden')
            ->generate();

        $this->assertHtmlEquals('
<input type="hidden" name="hidden" id="myFormId_hidden" />
',
            $html
        );
    }

    public function testSimpleHiddenInputComponentForBootstrap3()
    {
        $this->setDecorators(['bootstrap:v3']);
        $html = \Form::hidden('hidden')
            ->generate();

        $this->assertHtmlEquals(
            '
<input type="hidden" name="hidden" class="form-control" id="myFormId_hidden" />
',
            $html
        );
    }

    public function testSimpleHiddenInputComponentForBootstrap4()
    {
        $this->setDecorators(['bootstrap:v4']);
        $html = \Form::hidden('hidden')
            ->generate();

        $this->assertHtmlEquals(
            '
<input type="hidden" name="hidden" class="form-control" id="myFormId_hidden" />
',
            $html
        );
    }

    public function testComplexHiddenInputComponentForBootstrap4()
    {
        $this->setDecorators(['bootstrap:v4']);
        $element = \Form::hidden('hidden');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<input type="hidden" name="myFieldName" aria-describedby="describedById" class="myFirstClass mySecondClass form-control" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" aria-invalid="true" autofocus disabled readonly required value="myValue" />
',
            $element->generate()
        );
    }


}
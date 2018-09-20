<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class ButtonTest extends TestCase
{

    public function testSimpleButton()
    {
        $html = \Form::button('button')
            ->generate();

        $this->assertHtmlEquals('
<button type="button" name="button" id="myFormId_button">Button</button>
',
            $html
        );
    }

    public function testSimpleButtonForBootstrap3()
    {
        $this->setDecorators(['bootstrap:v3']);
        $html = \Form::button('button')
            ->generate();

        $this->assertHtmlEquals(
            '
<button type="button" name="button" class="btn btn-default" id="myFormId_button">Button</button>
',
            $html
        );
    }

    public function testSimpleButtonForBootstrap4()
    {
        $this->setDecorators(['bootstrap:v4']);
        $html = \Form::button('button')
            ->generate();

        $this->assertHtmlEquals(
            '
<button type="button" name="button" class="btn btn-default" id="myFormId_button">Button</button>
',
            $html
        );
    }

    public function testComplexButtonForBootstrap4()
    {
        $this->setDecorators(['bootstrap:v4']);
        $element = \Form::button('button');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<button type="button" name="myButtonName" aria-describedby="describedById" class="myFirstClass mySecondClass btn btn-default" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" autofocus disabled value="myValue">This is a button.</button>
',
            $element->generate()
        );
    }


}
<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class ResetButtonTest extends TestCase
{

    public function testSimpleButton()
    {
        $html = \Form::reset('reset')
            ->generate();

        $this->assertHtmlEquals('
<button type="reset" name="reset" id="myFormId_reset">Reset</button>
',
            $html
        );
    }

    public function testSimpleButtonForBootstrap3()
    {
        $this->setFrontendFramework('bootstrap', '3');
        $html = \Form::reset('reset')
            ->generate();

        $this->assertHtmlEquals(
            '
<button type="reset" name="reset" class="btn btn-secondary" id="myFormId_reset">Reset</button>
',
            $html
        );
    }

    public function testSimpleButtonForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $html = \Form::reset('reset')
            ->generate();

        $this->assertHtmlEquals(
            '
<button type="reset" name="reset" class="btn btn-secondary" id="myFormId_reset">Reset</button>
',
            $html
        );
    }

    public function testComplexButtonForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $element = \Form::reset('reset');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<button type="reset" name="myButtonName" aria-describedby="describedById" class="myFirstClass mySecondClass btn btn-secondary" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" autofocus disabled value="myValue">This is a button.</button>
',
            $element->generate()
        );
    }


}
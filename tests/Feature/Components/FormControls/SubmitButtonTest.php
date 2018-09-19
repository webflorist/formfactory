<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;

class SubmitButtonTest extends TestCase
{

    public function testSimpleButton()
    {
        $html = \Form::submit('submit')
            ->generate();

        $this->assertHtmlEquals('
<button type="submit" name="submit" id="myFormId_submit">Submit</button>
',
            $html
        );
    }

    public function testSimpleButtonForBootstrap3()
    {
        $this->setFrontendFramework('bootstrap', '3');
        $html = \Form::submit('submit')
            ->generate();

        $this->assertHtmlEquals(
            '
<button type="submit" name="submit" class="btn btn-primary" id="myFormId_submit">Submit</button>
',
            $html
        );
    }

    public function testSimpleButtonForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $html = \Form::submit('submit')
            ->generate();

        $this->assertHtmlEquals(
            '
<button type="submit" name="submit" class="btn btn-primary" id="myFormId_submit">Submit</button>
',
            $html
        );
    }

    public function testComplexButtonForBootstrap4()
    {
        $this->setFrontendFramework('bootstrap', '4');
        $element = \Form::submit('submit');

        $this->applyComplexAttributes($element);

        $this->assertHtmlEquals(
            '
<button type="submit" name="myButtonName" aria-describedby="describedById" class="myFirstClass mySecondClass btn btn-primary" data-my-first-data-attribute="myFirstDataAttributeValue" data-my-second-data-attribute="mySecondDataAttributeValue" hidden id="myId" role="myFirstRole mySecondRole" style="display:block;color:black" title="My Title" autofocus disabled value="myValue">This is a button.</button>
',
            $element->generate()
        );
    }


}
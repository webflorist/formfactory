<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class TextInputTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::text('text');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_text">Text</label>
                <input type="text" name="text" id="myFormId_text" placeholder="Text" />
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::text('text')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_text">Text<sup>*</sup></label>
                <div id="myFormId_text_errors">
                    <div>myFirstError</div>
                    <div>mySecondError</div>
                </div>
                <input type="text" name="text" id="myFormId_text" required pattern="[a-zA-Z]+" maxlength="10" placeholder="Text" aria-describedby="myFormId_text_errors myFormId_text_helpText" aria-invalid="true" />
                <small id="myFormId_text_helpText">myHelpText</small>
            ',
            $element->generate()
        );
    }

}
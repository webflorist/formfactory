<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class TextareaInputTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::textarea('textarea');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_textarea">Textarea</label>
                <textarea name="textarea" id="myFormId_textarea" placeholder="Textarea"></textarea>
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::textarea('textarea')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_textarea">Textarea<sup>*</sup></label>
                <div id="myFormId_textarea_errors">
                    <div>myFirstError</div>
                    <div>mySecondError</div>
                </div>
                <textarea name="textarea" id="myFormId_textarea" required maxlength="10" placeholder="Textarea" aria-describedby="myFormId_textarea_errors myFormId_textarea_helpText" aria-invalid="true"></textarea>
                <small id="myFormId_textarea_helpText">myHelpText</small>
            ',
            $element->generate()
        );
    }

}
<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class TextInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4';
    protected $decorators = ['bootstrap:v4'];

    public function testSimple()
    {
        $element = \Form::text('text');

        $this->assertHtmlEquals(
            '
                <div class="form-group">
                    <label for="myFormId_text">Text</label>
                    <input type="text" name="text" id="myFormId_text" class="form-control" placeholder="Text" />
                </div>
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
                <div class="form-group has-error">
                    <label for="myFormId_text">Text<sup>*</sup></label>
                    <div id="myFormId_text_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="text" name="text" id="myFormId_text" class="form-control" required pattern="[a-zA-Z]+" maxlength="10" placeholder="Text" aria-describedby="myFormId_text_errors myFormId_text_helpText" aria-invalid="true" />
                    <small id="myFormId_text_helpText">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
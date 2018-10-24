<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4Vue2;

use FormFactoryTests\TestCase;

class TextareaTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4_vue2';
    protected $decorators = ['bootstrap:v4'];
    protected $enableVue = true;

    public function testSimple()
    {
        $element = \Form::textarea('textarea');

        $this->assertHtmlEquals(
            '
                <div class="form-group">
                    <label for="myFormId_textarea">Textarea</label>
                    <textarea name="textarea" id="myFormId_textarea" class="form-control" placeholder="Textarea"></textarea>
                </div>
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
                <div class="form-group has-error">
                    <label for="myFormId_textarea">Textarea<sup v-if="fields.week.isRequired">*</sup></label>
                    <div id="myFormId_textarea_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <textarea name="textarea" id="myFormId_textarea" class="form-control" required maxlength="10" placeholder="Textarea" aria-describedby="myFormId_textarea_errors myFormId_textarea_helpText" aria-invalid="true"></textarea>
                    <small id="myFormId_textarea_helpText">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4Vue2;

use FormFactoryTests\TestCase;

class FileInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4_vue2';
    protected $decorators = ['bootstrap:v4'];
    protected $enableVue = true;

    public function testSimple()
    {
        $element = \Form::file('file');

        $this->assertHtmlEquals(
            '
                <div class="form-group" v-bind:class="{ \'has-error\': fieldHasError(\'text\') }">
                    <label for="myFormId_file">File</label>
                    <input type="file" name="file" id="myFormId_file" class="form-control-file" />
                </div>
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::file('file')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <div class="form-group" v-bind:class="{ \'has-error\': fieldHasError(\'text\') }">
                    <label for="myFormId_file">File<sup v-if="fields.week.isRequired">*</sup></label>
                    <div id="myFormId_file_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="file" name="file" id="myFormId_file" class="form-control-file" required aria-describedby="myFormId_file_errors myFormId_file_helpText" aria-invalid="true" />
                    <small id="myFormId_file_helpText">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
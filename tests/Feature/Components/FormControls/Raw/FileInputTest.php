<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class FileInputTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::file('file');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_file">File</label>
                <input type="file" name="file" id="myFormId_file" />
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
                <label for="myFormId_file">File<sup>*</sup></label>
                <div id="myFormId_file_errors">
                    <div>myFirstError</div>
                    <div>mySecondError</div>
                </div>
                <input type="file" name="file" id="myFormId_file" required aria-describedby="myFormId_file_errors myFormId_file_helpText" aria-invalid="true" />
                <small id="myFormId_file_helpText">myHelpText</small>
            ',
            $element->generate()
        );
    }

}
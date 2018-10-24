<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class UrlInputTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::url('url');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_url">Url</label>
                <input type="url" name="url" id="myFormId_url" placeholder="Url" />
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::url('url')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_url">Url<sup>*</sup></label>
                <div id="myFormId_url_errors">
                    <div>myFirstError</div>
                    <div>mySecondError</div>
                </div>
                <input type="url" name="url" id="myFormId_url" required aria-describedby="myFormId_url_errors myFormId_url_helpText" aria-invalid="true" pattern="[a-zA-Z]+" maxlength="10" placeholder="Url" />
                <small id="myFormId_url_helpText">myHelpText</small>
            ',
            $element->generate()
        );
    }

}
<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class SearchInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4';
    protected $decorators = ['bootstrap:v4'];

    public function testSimple()
    {
        $element = \Form::search('search');

        $this->assertHtmlEquals(
            '
                <div class="form-group">
                    <label for="myFormId_search">Search</label>
                    <input type="search" name="search" id="myFormId_search" class="form-control" placeholder="Search" />
                </div>
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::search('search')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <div class="form-group has-error">
                    <label for="myFormId_search">Search<sup>*</sup></label>
                    <div id="myFormId_search_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="search" name="search" id="myFormId_search" class="form-control" required pattern="[a-zA-Z]+" maxlength="10" placeholder="Search" aria-describedby="myFormId_search_errors myFormId_search_helpText" aria-invalid="true" />
                    <small id="myFormId_search_helpText" class="form-text text-muted">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
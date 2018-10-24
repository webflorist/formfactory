<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class SearchInputTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::search('search');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_search">Search</label>
                <input type="search" name="search" id="myFormId_search" placeholder="Search" />
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
                <label for="myFormId_search">Search<sup>*</sup></label>
                <div id="myFormId_search_errors">
                    <div>myFirstError</div>
                    <div>mySecondError</div>
                </div>
                <input type="search" name="search" id="myFormId_search" required aria-describedby="myFormId_search_errors myFormId_search_helpText" aria-invalid="true" pattern="[a-zA-Z]+" maxlength="10" placeholder="Search" />
                <small id="myFormId_search_helpText">myHelpText</small>
            ',
            $element->generate()
        );
    }

}
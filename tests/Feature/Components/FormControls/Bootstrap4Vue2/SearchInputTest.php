<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4Vue2;

use FormFactoryTests\TestCase;

class SearchInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4_vue2';
    protected $decorators = ['bootstrap:v4'];
    protected $enableVue = true;

    public function testSimple()
    {
        $element = \Form::search('search');

        $this->assertHtmlEquals(
            '
                <div class="form-group" v-bind:class="{ \'has-error\': fieldHasError(\'text\') }">
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
                <div class="form-group" v-bind:class="{ \'has-error\': fieldHasError(\'text\') }">
                    <label for="myFormId_search">Search<sup v-if="fields.week.isRequired">*</sup></label>
                    <div id="myFormId_search_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="search" name="search" id="myFormId_search" class="form-control" required pattern="[a-zA-Z]+" maxlength="10" placeholder="Search" aria-describedby="myFormId_search_errors myFormId_search_helpText" aria-invalid="true" />
                    <small id="myFormId_search_helpText">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
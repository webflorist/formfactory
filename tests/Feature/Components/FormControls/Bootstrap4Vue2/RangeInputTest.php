<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4Vue2;

use FormFactoryTests\TestCase;

class RangeInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4_vue2';
    protected $decorators = ['bootstrap:v4'];
    protected $enableVue = true;

    public function testSimple()
    {
        $element = \Form::range('range');

        $this->assertHtmlEquals(
            '
                <div class="form-group">
                    <label for="myFormId_range">Range</label>
                    <input type="range" name="range" id="myFormId_range" class="form-control" />
                </div>
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::range('range')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <div class="form-group has-error">
                    <label for="myFormId_range">Range<sup v-if="fields.week.isRequired">*</sup></label>
                    <div id="myFormId_range_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="range" name="range" id="myFormId_range" class="form-control" required aria-describedby="myFormId_range_errors myFormId_range_helpText" aria-invalid="true" />
                    <small id="myFormId_range_helpText">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
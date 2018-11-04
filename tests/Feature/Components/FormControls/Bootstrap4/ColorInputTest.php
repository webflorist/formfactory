<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class ColorInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4';
    protected $decorators = ['bootstrap:v4'];

    public function testSimple()
    {
        $element = \Form::color('color');

        $this->assertHtmlEquals(
            '
                <div class="form-group">
                    <label for="myFormId_color">Color</label>
                    <input type="color" name="color" id="myFormId_color" class="form-control" />
                </div>
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::color('color')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <div class="form-group has-error">
                    <label for="myFormId_color">Color<sup>*</sup></label>
                    <div id="myFormId_color_errors" role="alert" class="alert m-b-1 alert-danger">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="color" name="color" id="myFormId_color" class="form-control" required aria-describedby="myFormId_color_errors myFormId_color_helpText" aria-invalid="true" />
                    <small id="myFormId_color_helpText" class="form-text text-muted">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
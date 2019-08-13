<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class InputGroupTest extends TestCase
{

    protected $decorators = ['bootstrap:v4'];

    public function testSimple()
    {
        $element = \Form::inputGroup([
            'text-before',
            \Form::text('myTextFieldName'),
            \Form::select('mySelectFieldName',
                [
                    \Form::option('myOptionValue1'),
                    \Form::option('myOptionValue2'),
                ]),
            \Form::submit(),
        ]);

        $this->assertHtmlEquals(
            '
                <div class="form-group">
                    <label for="myFormId_myTextFieldName">MyTextFieldName</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">text-before</span></div>
                        <input type="text" name="myTextFieldName" id="myFormId_myTextFieldName" placeholder="MyTextFieldName" class="form-control" />
                        <div class="input-group-append">
                            <select name="mySelectFieldName" id="myFormId_mySelectFieldName" class="form-control">
                                <option value="myOptionValue1">myOptionValue1</option>
                                <option value="myOptionValue2">myOptionValue2</option>
                            </select>
                            <button type="submit" name="submit" id="myFormId_submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            ',
            $element->generate()
        );
    }

    public function testComplexInputGroup()
    {
        $element = \Form::inputGroup([
            'text-before',
            \Form::text('myTextFieldName')
                ->helpText('myHelpText')
                ->errors(['myFirstError', 'mySecondError'])
                ->rules('required|alpha|max:10'),
            \Form::select('mySelectFieldName',
                [
                    \Form::option('myOptionValue1'),
                    \Form::option('myOptionValue2'),
                ]),
            \Form::submit(),
        ]);

        $this->assertHtmlEquals(
            '
                <div class="form-group">
                    <label for="myFormId_myTextFieldName">MyTextFieldName<sup>*</sup></label>
                    <div id="myFormId_myTextFieldName_errors" role="alert" class="invalid-feedback">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">text-before</span></div>
                        <input type="text" name="myTextFieldName" id="myFormId_myTextFieldName" class="form-control is-invalid" required pattern="[a-zA-Z]+" maxlength="10" placeholder="MyTextFieldName" aria-describedby="myFormId_myTextFieldName_helpText myFormId_myTextFieldName_errors" aria-invalid="true" />
                        <div class="input-group-append">
                            <select name="mySelectFieldName" id="myFormId_mySelectFieldName" class="form-control">
                                <option value="myOptionValue1">myOptionValue1</option>
                                <option value="myOptionValue2">myOptionValue2</option>
                            </select>
                            <button type="submit" name="submit" id="myFormId_submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    <small id="myFormId_myTextFieldName_helpText" class="text-muted form-text small">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
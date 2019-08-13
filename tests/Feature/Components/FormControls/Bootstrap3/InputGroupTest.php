<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap3;

use FormFactoryTests\TestCase;

class InputGroupTest extends TestCase
{

    protected $decorators = ['bootstrap:v3'];

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
                        <span class="input-group-addon">text-before</span>
                        <input type="text" name="myTextFieldName" id="myFormId_myTextFieldName" class="form-control" placeholder="MyTextFieldName" />
                        <select name="mySelectFieldName" id="myFormId_mySelectFieldName" class="form-control">
                            <option value="myOptionValue1">myOptionValue1</option>
                            <option value="myOptionValue2">myOptionValue2</option>
                        </select>
                        <span class="input-group-btn">
                            <button type="submit" name="submit" id="myFormId_submit" class="btn btn-primary">Submit</button>
                        </span>
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
                <div class="form-group has-error">
                    <label for="myFormId_myTextFieldName">MyTextFieldName<sup>*</sup></label>
                    <div id="myFormId_myTextFieldName_errors" class="alert alert-danger" role="alert">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">text-before</span>
                        <input type="text" name="myTextFieldName" id="myFormId_myTextFieldName" aria-describedby="myFormId_myTextFieldName_helpText myFormId_myTextFieldName_errors" aria-invalid="true" class="form-control" required pattern="[a-zA-Z]+" maxlength="10" placeholder="MyTextFieldName" />
                        <select name="mySelectFieldName" id="myFormId_mySelectFieldName" class="form-control">
                            <option value="myOptionValue1">myOptionValue1</option>
                            <option value="myOptionValue2">myOptionValue2</option>
                        </select>
                        <span class="input-group-btn">
                            <button type="submit" name="submit" id="myFormId_submit" class="btn btn-primary">Submit</button>
                        </span>
                    </div>
                    <small id="myFormId_myTextFieldName_helpText" class="text-muted small">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
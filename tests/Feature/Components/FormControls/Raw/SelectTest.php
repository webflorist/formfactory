<?php

namespace FormFactoryTests\Feature\Components\FormControls\Raw;

use FormFactoryTests\TestCase;

class SelectTest extends TestCase
{



    public function testSimple()
    {
        $element = \Form::select('myFieldName');

        $this->assertHtmlEquals(
            '
                <div>
                    <label for="myFormId_myFieldName">MyFieldName</label>
                    <select name="myFieldName" id="myFormId_myFieldName"></select>
                </div>
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::select(
            'myFieldName',
            [
                \Form::option('myOption1'),
                \Form::optgroup('myOptgroup1',[
                    \Form::option('myOptgroup1-myOption1'),
                    \Form::option('myOptgroup1-myOption2')
                ]),
                \Form::option('myOption2'),
                \Form::optgroup('myOptgroup2',[
                    \Form::option('myOptgroup2-myOption1'),
                    \Form::option('myOptgroup2-myOption2')
                ]),
            ])
            ->helpText('myHelpText')
            ->errors(['myFirstError','mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <div>
                    <label for="myFormId_myFieldName">MyFieldName<sup>*</sup></label>
                    <div id="myFormId_myFieldName_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <select name="myFieldName" id="myFormId_myFieldName" required aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" aria-invalid="true">
                        <option value="myOption1">myOption1</option>
                        <optgroup label="myOptgroup1">
                            <option value="myOptgroup1-myOption1">myOptgroup1-myOption1</option>
                            <option value="myOptgroup1-myOption2">myOptgroup1-myOption2</option>
                        </optgroup>
                        <option value="myOption2">myOption2</option>
                        <optgroup label="myOptgroup2">
                            <option value="myOptgroup2-myOption1">myOptgroup2-myOption1</option>
                            <option value="myOptgroup2-myOption2">myOptgroup2-myOption2</option>
                        </optgroup>
                    </select>
                    <small id="myFormId_myFieldName_helpText">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
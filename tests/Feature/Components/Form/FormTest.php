<?php

namespace FormFactoryTests\Feature\Components\FormControls\Form;

use Form;
use FormFactoryTests\TestCase;

class FormTest extends TestCase
{

    protected $openForm = false;
    protected $closeForm = false;

    public function testGlobalValues()
    {

        $html =
            Form::open('myFormId')
                ->action('myAction')
                ->values([
                    'myTextFieldName' => 'myTextFieldValue',
                    'myRadioFieldName' => 'myRadioFieldValue2',
                    'myTextareaFieldName' => 'myTextAreaFieldValue'
                ])->generate()
            .
            Form::text('myTextFieldName')->generate()
            .
            Form::radio('myRadioFieldValue1', 'myRadioFieldName')->generate()
            .
            Form::radio('myRadioFieldValue2', 'myRadioFieldName')->generate()
            .
            Form::textarea('myTextareaFieldName')->generate()
            .
            Form::close()
        ;

        $this->assertHtmlEquals(
            '
                <form role="form" accept-charset="UTF-8" enctype="multipart/form-data" id="myFormId" method="POST" action="myAction">
                
                    <input type="hidden" name="_token" id="myFormId__token" value="" />
                    <input type="hidden" name="_formID" id="myFormId__formID" value="myFormId" />
                    
                    <div>
                        <label for="myFormId_myTextFieldName">MyTextFieldName</label>
                        <input type="text" name="myTextFieldName" id="myFormId_myTextFieldName" placeholder="MyTextFieldName" value="myTextFieldValue" />
                    </div>
                    
                    <div>
                        <input type="radio" name="myRadioFieldName" value="myRadioFieldValue1" id="myFormId_myRadioFieldName_myRadioFieldValue1" />
                        <label for="myFormId_myRadioFieldName_myRadioFieldValue1">MyRadioFieldValue1</label>
                    </div>
                    
                    <div>
                        <input type="radio" name="myRadioFieldName" value="myRadioFieldValue2" id="myFormId_myRadioFieldName_myRadioFieldValue2" checked />
                        <label for="myFormId_myRadioFieldName_myRadioFieldValue2">MyRadioFieldValue2</label>
                    </div>
                    
                    <div>
                        <label for="myFormId_myTextareaFieldName">MyTextareaFieldName</label>
                        <textarea name="myTextareaFieldName" id="myFormId_myTextareaFieldName" placeholder="MyTextareaFieldName">myTextAreaFieldValue</textarea>
                    </div>
                    
                </form>

            ',
            $html
        );
    }

    public function testRequiredFieldsLegendPresent()
    {

        $html =
            Form::open('myFormId')->generate()
            .
            Form::text('myTextFieldName')->required()->generate()
            .
            Form::close()
        ;

        $this->assertHtmlEquals(
            '
                <form id="myFormId" role="form" accept-charset="UTF-8" enctype="multipart/form-data" method="POST" action="http://localhost:8000">
                    <input type="hidden" name="_token" id="myFormId__token" value="" />
                    <input type="hidden" name="_formID" id="myFormId__formID" value="myFormId" />
                    <div>
                        <label for="myFormId_myTextFieldName">MyTextFieldName<sup>*</sup></label>
                        <input type="text" name="myTextFieldName" id="myFormId_myTextFieldName" required placeholder="MyTextFieldName" />
                    </div>
                    <div class="small"><sup>*</sup> Mandatory fields</div>
                </form>
            ',
            $html
        );
    }

    public function testUnclaimedErrors()
    {

        $html =
            Form::open('myFormId')
                ->action('myAction')
                ->errors([
                    'myFirstUnclaimedErrors' => [
                        'myFirstUnclaimedError1',
                        'myFirstUnclaimedError2'
                    ],
                    'mySecondUnclaimedErrors' => [
                        'mySecondUnclaimedError1',
                        'mySecondUnclaimedError2'
                    ],
                    'myTextFieldName' => [
                        'myFirstArrayItemError1',
                        'myFirstArrayItemError2'
                    ],
                ])->generate()
            .
            Form::text('myTextFieldName')->generate()
            .
            Form::close()
        ;

        $this->assertHtmlEquals(
            '
                <form id="myFormId" role="form" accept-charset="UTF-8" enctype="multipart/form-data" method="POST" action="myAction">
                    <input type="hidden" name="_token" id="myFormId__token" value="" />
                    <input type="hidden" name="_formID" id="myFormId__formID" value="myFormId" />
                    <div>
                        <label for="myFormId_myTextFieldName">MyTextFieldName</label>
                        <div id="myFormId_myTextFieldName_errors">
                            <div>myFirstArrayItemError1</div>
                            <div>myFirstArrayItemError2</div>
                        </div>
                        <input type="text" name="myTextFieldName" id="myFormId_myTextFieldName" placeholder="MyTextFieldName" aria-describedby="myFormId_myTextFieldName_errors" aria-invalid="true" />
                    </div>
                    <div id="myFormId_myFirstUnclaimedErrors_errors">
                        <div>myFirstUnclaimedError1</div>
                        <div>myFirstUnclaimedError2</div>
                    </div>
                    <div id="myFormId_mySecondUnclaimedErrors_errors">
                        <div>mySecondUnclaimedError1</div>
                        <div>mySecondUnclaimedError2</div>
                    </div>
                </form>
            ',
            $html
        );
    }

    public function testAdditionalErrorFields()
    {

        $html =
            Form::open('myFormId')
                ->action('myAction')
                ->errors([
                    'myFirstAdditionalFieldName' => [
                        'myFirstAdditionalFieldError1',
                        'myFirstAdditionalFieldError2'
                    ],
                    'mySecondAdditionalFieldName' => [
                        'mySecondAdditionalFieldError1',
                        'mySecondAdditionalFieldError2'
                    ],
                    'myTextFieldName' => [
                        'myTextFieldError1',
                        'myTextFieldError2'
                    ],
                ])->generate()
            .
            Form::text('myTextFieldName')
                ->addErrorField('myFirstAdditionalFieldName')
                ->addErrorField('mySecondAdditionalFieldName')
                ->generate()
            .
            Form::close()
        ;

        $this->assertHtmlEquals(
            '
                <form id="myFormId" role="form" accept-charset="UTF-8" enctype="multipart/form-data" method="POST" action="myAction">
                    <input type="hidden" name="_token" id="myFormId__token" value="" />
                    <input type="hidden" name="_formID" id="myFormId__formID" value="myFormId" />
                    <div>
                        <label for="myFormId_myTextFieldName">MyTextFieldName</label>
                        <div id="myFormId_myTextFieldName_errors">
                            <div>myTextFieldError1</div>
                            <div>myTextFieldError2</div>
                            <div>myFirstAdditionalFieldError1</div>
                            <div>myFirstAdditionalFieldError2</div>
                            <div>mySecondAdditionalFieldError1</div>
                            <div>mySecondAdditionalFieldError2</div>
                        </div>
                        <input type="text" name="myTextFieldName" id="myFormId_myTextFieldName" placeholder="MyTextFieldName" aria-describedby="myFormId_myTextFieldName_errors" aria-invalid="true" />
                    </div>
                </form>
            ',
            $html
        );
    }

    public function testErrorContainer()
    {

        $html =
            Form::open('myFormId')
                ->errors([
                    'myFieldName' => [
                        'myFirstError',
                        'mySecondError'
                    ]
                ])->generate()
            .
            Form::errorContainer('myFieldName')
            .
            Form::close()
        ;

        $this->assertHtmlEquals(
            '
                <form id="myFormId" role="form" accept-charset="UTF-8" enctype="multipart/form-data" method="POST" action="http://localhost:8000">
                    <input type="hidden" name="_token" id="myFormId__token" value="" />
                    <input type="hidden" name="_formID" id="myFormId__formID" value="myFormId" />
                    <div id="myFormId_myFieldName_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                </form>
            ',
            $html
        );
    }

    public function testAdditionalErrorFieldsInInputGroup()
    {

        $html =
            Form::open('myFormId')
                ->action('myAction')
                ->errors([
                    'myFirstAdditionalFieldName' => [
                        'myFirstAdditionalFieldError1',
                        'myFirstAdditionalFieldError2'
                    ],
                    'mySecondAdditionalFieldName' => [
                        'mySecondAdditionalFieldError1',
                        'mySecondAdditionalFieldError2'
                    ],
                    'myTextFieldName' => [
                        'myTextFieldError1',
                        'myTextFieldError2'
                    ],
                    'mySelectFieldName' => [
                        'mySelectFieldError1',
                        'mySelectFieldError2'
                    ],
                ])->generate()
            .
            \Form::inputGroup([
                'text-before',
                \Form::text('myTextFieldName')
                    ->addErrorField('myFirstAdditionalFieldName')
                    ->addErrorField('mySecondAdditionalFieldName'),
                \Form::select('mySelectFieldName',
                    [
                        \Form::option('myOptionValue1'),
                        \Form::option('myOptionValue2'),
                    ]),
                \Form::submit(),
            ])->generate()
            .
            Form::close()
        ;

        $this->assertHtmlEquals(
            '
                <form id="myFormId" role="form" accept-charset="UTF-8" enctype="multipart/form-data" method="POST" action="myAction">
                    <input type="hidden" name="_token" id="myFormId__token" value="" />
                    <input type="hidden" name="_formID" id="myFormId__formID" value="myFormId" />
                    <div>
                        <label for="myFormId_myTextFieldName">MyTextFieldName</label>
                        <div id="myFormId_myTextFieldName_errors">
                            <div>myTextFieldError1</div>
                            <div>myTextFieldError2</div>
                            <div>myFirstAdditionalFieldError1</div>
                            <div>myFirstAdditionalFieldError2</div>
                            <div>mySecondAdditionalFieldError1</div>
                            <div>mySecondAdditionalFieldError2</div>
                        </div>
                        <div id="myFormId_mySelectFieldName_errors">
                            <div>mySelectFieldError1</div>
                            <div>mySelectFieldError2</div>
                        </div>
                        <div>
                            text-before
                            <input type="text" name="myTextFieldName" id="myFormId_myTextFieldName" placeholder="MyTextFieldName" aria-describedby="myFormId_myTextFieldName_errors" aria-invalid="true" />
                            <select name="mySelectFieldName" id="myFormId_mySelectFieldName" aria-describedby="myFormId_mySelectFieldName_errors" aria-invalid="true">
                                <option value="myOptionValue1">myOptionValue1</option>
                                <option value="myOptionValue2">myOptionValue2</option>
                            </select>
                            <button type="submit" name="submit" id="myFormId_submit">Submit</button>
                        </div>
                    </div>
                </form>
            ',
            $html
        );
    }

}
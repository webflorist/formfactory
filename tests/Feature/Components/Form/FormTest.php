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
                ])
            .
            Form::text('myTextFieldName')
            .
            Form::radio('myRadioFieldValue1', 'myRadioFieldName')
            .
            Form::radio('myRadioFieldValue2', 'myRadioFieldName')
            .
            Form::textarea('myTextareaFieldName')
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
            Form::open('myFormId')
            .
            Form::text('myTextFieldName')->required()
            .
            Form::close()
        ;

        $this->assertHtmlEquals(
            '
                <form id="myFormId" role="form" accept-charset="UTF-8" enctype="multipart/form-data" method="POST" action="http://192.168.56.103:8000">
                    <input type="hidden" name="_token" id="myFormId__token" value="" />
                    <input type="hidden" name="_formID" id="myFormId__formID" value="myFormId" />
                    <div>
                        <label for="myFormId_myTextFieldName">MyTextFieldName<sup>*</sup></label>
                        <input type="text" name="myTextFieldName" id="myFormId_myTextFieldName" required placeholder="MyTextFieldName" />
                    </div>
                    <div class="text-muted small"><sup>*</sup> Mandatory fields</div>
                </form>
            ',
            $html
        );
    }

}
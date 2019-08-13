<?php

namespace FormFactoryTests\Feature\Components\FormControls\Form;

use Form;
use FormFactoryTests\TestCase;

class VueFormTest extends TestCase
{

    protected $openForm = false;
    protected $closeForm = false;
    protected $vueEnabled = true;

    public function testGlobalValues()
    {

        $html =
            Form::vOpen('myFormId')
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
                <form id="myFormId" role="form" accept-charset="UTF-8" enctype="multipart/form-data" method="POST" action="myAction" v-on:submit.prevent="submitForm">
                    <template>
                        <div role="alert" v-if="generalErrors.length">
                            <div v-for="error in generalErrors">{{ error }}</div>
                        </div>
                    </template>
                    <template>
                        <div v-if="successMessage.length" role="alert">{{ successMessage }}</div>
                    </template>
                    <input type="hidden" name="_token" id="myFormId__token" value="" />
                    <input type="hidden" name="_formID" id="myFormId__formID" value="myFormId" />
                    <div>
                        <label for="myFormId_myTextFieldName">
                            MyTextFieldName
                            <template><sup v-if="fields[\'myTextFieldName\'].isRequired">*</sup></template>
                        </label>
                        <template>
                            <div id="myFormId_myTextFieldName_errors" v-if="fieldHasError(\'myTextFieldName\')">
                                <div v-for="error in fields[\'myTextFieldName\'].errors">{{ error }}</div>
                            </div>
                        </template>
                        <input type="text" name="myTextFieldName" id="myFormId_myTextFieldName" value="myTextFieldValue" placeholder="MyTextFieldName" v-model="fields[\'myTextFieldName\'].value" v-bind:required="fields[\'myTextFieldName\'].isRequired" v-bind:disabled="fields[\'myTextFieldName\'].isDisabled" v-bind:aria-invalid="fieldHasError(\'myTextFieldName\')" aria-describedby="myFormId_myTextFieldName_errors" />
                    </div>
                    <div>
                        <template>
                            <div id="myFormId_myRadioFieldName_errors" v-if="fieldHasError(\'myRadioFieldName\')">
                                <div v-for="error in fields[\'myRadioFieldName\'].errors">{{ error }}</div>
                            </div>
                        </template>
                        <input type="radio" name="myRadioFieldName" value="myRadioFieldValue1" id="myFormId_myRadioFieldName_myRadioFieldValue1" v-model="fields[\'myRadioFieldName\'].value" v-bind:required="fields[\'myRadioFieldName\'].isRequired" v-bind:disabled="fields[\'myRadioFieldName\'].isDisabled" v-bind:aria-invalid="fieldHasError(\'myRadioFieldName\')" aria-describedby="myFormId_myRadioFieldName_errors" />
                        <label for="myFormId_myRadioFieldName_myRadioFieldValue1">
                            MyRadioFieldValue1
                            <template><sup v-if="fields[\'myRadioFieldName\'].isRequired">*</sup></template>
                        </label>
                    </div>
                    <div>
                        <template>
                            <div id="myFormId_myRadioFieldName_errors" v-if="fieldHasError(\'myRadioFieldName\')">
                                <div v-for="error in fields[\'myRadioFieldName\'].errors">{{ error }}</div>
                            </div>
                        </template>
                        <input type="radio" name="myRadioFieldName" value="myRadioFieldValue2" id="myFormId_myRadioFieldName_myRadioFieldValue2" checked v-model="fields[\'myRadioFieldName\'].value" v-bind:required="fields[\'myRadioFieldName\'].isRequired" v-bind:disabled="fields[\'myRadioFieldName\'].isDisabled" v-bind:aria-invalid="fieldHasError(\'myRadioFieldName\')" aria-describedby="myFormId_myRadioFieldName_errors" />
                        <label for="myFormId_myRadioFieldName_myRadioFieldValue2">
                            MyRadioFieldValue2
                            <template><sup v-if="fields[\'myRadioFieldName\'].isRequired">*</sup></template>
                        </label>
                    </div>
                    <div>
                        <label for="myFormId_myTextareaFieldName">
                            MyTextareaFieldName
                            <template><sup v-if="fields[\'myTextareaFieldName\'].isRequired">*</sup></template>
                        </label>
                        <template>
                            <div id="myFormId_myTextareaFieldName_errors" v-if="fieldHasError(\'myTextareaFieldName\')">
                                <div v-for="error in fields[\'myTextareaFieldName\'].errors">{{ error }}</div>
                            </div>
                        </template>
                        <textarea name="myTextareaFieldName" id="myFormId_myTextareaFieldName" placeholder="MyTextareaFieldName" v-model="fields[\'myTextareaFieldName\'].value" v-bind:required="fields[\'myTextareaFieldName\'].isRequired" v-bind:disabled="fields[\'myTextareaFieldName\'].isDisabled" v-bind:aria-invalid="fieldHasError(\'myTextareaFieldName\')" aria-describedby="myFormId_myTextareaFieldName_errors">myTextAreaFieldValue</textarea>
                    </div>
                    <div class="text-muted small"><sup>*</sup> Mandatory fields</div>
                </form>

            ',
            $html
        );
    }

    public function testRequiredFieldsLegendPresent()
    {

        $html =
            Form::vOpen('myFormId')->generate()
            .
            Form::text('myTextFieldName')->required()->generate()
            .
            Form::close()
        ;

        $this->assertHtmlEquals(
            '
                <form id="myFormId" role="form" accept-charset="UTF-8" enctype="multipart/form-data" method="POST" v-on:submit.prevent="submitForm" action="http://localhost:8000">
                    <template>
                        <div role="alert" v-if="generalErrors.length">
                            <div v-for="error in generalErrors">{{ error }}</div>
                        </div>
                    </template>
                    <template>
                        <div v-if="successMessage.length" role="alert">{{ successMessage }}</div>
                    </template>
                    <input type="hidden" name="_token" id="myFormId__token" value="" />
                    <input type="hidden" name="_formID" id="myFormId__formID" value="myFormId" />
                    <div>
                        <label for="myFormId_myTextFieldName">
                            MyTextFieldName
                            <template><sup v-if="fields[\'myTextFieldName\'].isRequired">*</sup></template>
                        </label>
                        <template>
                            <div id="myFormId_myTextFieldName_errors" v-if="fieldHasError(\'myTextFieldName\')">
                                <div v-for="error in fields[\'myTextFieldName\'].errors">{{ error }}</div>
                            </div>
                        </template>
                        <input type="text" name="myTextFieldName" id="myFormId_myTextFieldName" required placeholder="MyTextFieldName" v-model="fields[\'myTextFieldName\'].value" v-bind:required="fields[\'myTextFieldName\'].isRequired" v-bind:disabled="fields[\'myTextFieldName\'].isDisabled" v-bind:aria-invalid="fieldHasError(\'myTextFieldName\')" aria-describedby="myFormId_myTextFieldName_errors" />
                    </div>
                    <div class="text-muted small"><sup>*</sup> Mandatory fields</div>
                </form>
            ',
            $html
        );
    }

    public function testAdditionalErrorFields()
    {

        $html =
            Form::vOpen('myFormId')
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
                <form id="myFormId" role="form" accept-charset="UTF-8" enctype="multipart/form-data" method="POST" action="myAction" v-on:submit.prevent="submitForm">
                    <template>
                        <div role="alert" v-if="generalErrors.length">
                            <div v-for="error in generalErrors">{{ error }}</div>
                        </div>
                    </template>
                    <template>
                        <div v-if="successMessage.length" role="alert">{{ successMessage }}</div>
                    </template>
                    <input type="hidden" name="_token" id="myFormId__token" value="" />
                    <input type="hidden" name="_formID" id="myFormId__formID" value="myFormId" />
                    <div>
                        <label for="myFormId_myTextFieldName">
                            MyTextFieldName
                            <template><sup v-if="fields[\'myTextFieldName\'].isRequired">*</sup></template>
                        </label>
                        <template>
                            <div id="myFormId_myTextFieldName_errors" v-if="fieldHasError(\'myTextFieldName\') || fieldHasError(\'myFirstAdditionalFieldName\') || fieldHasError(\'mySecondAdditionalFieldName\')">
                                <div v-for="error in fields[\'myTextFieldName\'].errors">{{ error }}</div>
                                <div v-for="error in fields[\'myFirstAdditionalFieldName\'].errors">{{ error }}</div>
                                <div v-for="error in fields[\'mySecondAdditionalFieldName\'].errors">{{ error }}</div>
                            </div>
                        </template>
                        <input type="text" name="myTextFieldName" id="myFormId_myTextFieldName" placeholder="MyTextFieldName" v-model="fields[\'myTextFieldName\'].value" v-bind:required="fields[\'myTextFieldName\'].isRequired" v-bind:disabled="fields[\'myTextFieldName\'].isDisabled" v-bind:aria-invalid="fieldHasError(\'myTextFieldName\')" aria-describedby="myFormId_myTextFieldName_errors" />
                    </div>
                    <div class="text-muted small"><sup>*</sup> Mandatory fields</div>
                </form>
            ',
            $html
        );
    }

    public function testErrorContainer()
    {

        $html =
            Form::vOpen('myFormId')
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
                <form id="myFormId" role="form" accept-charset="UTF-8" enctype="multipart/form-data" method="POST" v-on:submit.prevent="submitForm" action="http://localhost:8000">
                    <template>
                        <div role="alert" v-if="generalErrors.length">
                            <div v-for="error in generalErrors">{{ error }}</div>
                        </div>
                    </template>
                    <template>
                        <div v-if="successMessage.length" role="alert">{{ successMessage }}</div>
                    </template>
                    <input type="hidden" name="_token" id="myFormId__token" value="" />
                    <input type="hidden" name="_formID" id="myFormId__formID" value="myFormId" />
                    <template>
                        <div id="myFormId_myFieldName_errors" v-if="fieldHasError(\'myFieldName\')">
                            <div v-for="error in fields[\'myFieldName\'].errors">{{ error }}</div>
                        </div>
                    </template>
                </form>
            ',
            $html
        );
    }

}
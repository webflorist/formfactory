<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4Vue;

class RadioGroupTest extends AbstractBootstrap4VueTest
{

    public function testSimple()
    {
        $element = \Form::radioGroup('myFieldName', [
            \Form::radio('myValue1'),
            \Form::radio('myValue2'),
        ]);

        $this->assertHtmlEquals(
            '
                <fieldset class="form-group">
                    <legend>MyFieldName<template><sup v-if="fields[\'myFieldName\'].isRequired">*</sup></template></legend>
		            <template>           
	                    <div role="alert" class="alert m-b-1 alert-danger" id="myFormId_myFieldName_errors" v-if="fieldHasError(\'myFieldName\')">
	                        <div v-for="error in fields[\'myFieldName\'].errors"> {{ error }} </div>
	                    </div>
		            </template>
                    <div class="form-check" v-bind:class="{ \'has-error\': fieldHasError(\'myFieldName\') }">
                        <input type="radio" name="myFieldName" class="custom-control-input" value="myValue1" id="myFormId_myFieldName_myValue1" aria-describedby="myFormId_myFieldName_errors" v-model="fields[\'myFieldName\'].value" v-bind:required="fields[\'myFieldName\'].isRequired" v-bind:disabled="fields[\'myFieldName\'].isDisabled" v-bind:aria-invalid="fieldHasError(\'myFieldName\')" />
                        <label class="form-check-label" for="myFormId_myFieldName_myValue1">MyValue1</label>
                    </div>
                    <div class="form-check" v-bind:class="{ \'has-error\': fieldHasError(\'myFieldName\') }">
                        <input type="radio" name="myFieldName" class="custom-control-input" value="myValue2" id="myFormId_myFieldName_myValue2" aria-describedby="myFormId_myFieldName_errors" v-model="fields[\'myFieldName\'].value" v-bind:required="fields[\'myFieldName\'].isRequired" v-bind:disabled="fields[\'myFieldName\'].isDisabled" v-bind:aria-invalid="fieldHasError(\'myFieldName\')" />
                        <label class="form-check-label" for="myFormId_myFieldName_myValue2">MyValue2</label>
                    </div>
                </fieldset>
            ',
            $element->generate()
        );
    }


    public function testComplex()
    {
        $element = \Form::radioGroup('myFieldName', [
            \Form::radio('myValue1'),
            \Form::radio('myValue2'),
        ])->legend('myLegend')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <fieldset class="form-group">
                    <legend>myLegend<template><sup v-if="fields[\'myFieldName\'].isRequired">*</sup></template></legend>
		            <template>           
	                    <div role="alert" class="alert m-b-1 alert-danger" id="myFormId_myFieldName_errors" v-if="fieldHasError(\'myFieldName\')">
	                        <div v-for="error in fields[\'myFieldName\'].errors"> {{ error }} </div>
	                    </div>
		            </template>
                    <div class="form-check" v-bind:class="{ \'has-error\': fieldHasError(\'myFieldName\') }">
                        <input type="radio" name="myFieldName" class="custom-control-input" value="myValue1" id="myFormId_myFieldName_myValue1" aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" required v-model="fields[\'myFieldName\'].value" v-bind:required="fields[\'myFieldName\'].isRequired" v-bind:disabled="fields[\'myFieldName\'].isDisabled" v-bind:aria-invalid="fieldHasError(\'myFieldName\')" />
                        <label class="form-check-label" for="myFormId_myFieldName_myValue1">MyValue1</label>                 
                    </div>
                    <div class="form-check" v-bind:class="{ \'has-error\': fieldHasError(\'myFieldName\') }">
                        <input type="radio" name="myFieldName" class="custom-control-input" value="myValue2" id="myFormId_myFieldName_myValue2" aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" required v-model="fields[\'myFieldName\'].value" v-bind:required="fields[\'myFieldName\'].isRequired" v-bind:disabled="fields[\'myFieldName\'].isDisabled" v-bind:aria-invalid="fieldHasError(\'myFieldName\')" />
                        <label class="form-check-label" for="myFormId_myFieldName_myValue2">MyValue2</label>                    
                    </div>
                    <small id="myFormId_myFieldName_helpText" class="text-muted form-text small">myHelpText</small>
                </fieldset>
            ',
            $element->generate()
        );
    }


}
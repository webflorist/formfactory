<?php

namespace FormFactoryTests\Feature\Components\FormControls\Bootstrap4Vue;

class RadioInputTest extends AbstractBootstrap4VueTest
{

    public function testSimple()
    {
        $element = \Form::radio('myValue', 'myFieldName');

        $this->assertHtmlEquals(
            '
                <div class="form-group custom-control custom-radio">
		            <template>           
	                    <div class="invalid-feedback d-block" role="alert" id="myFormId_myFieldName_errors" v-if="fieldHasError(\'myFieldName\')">
	                        <div v-for="error in fields[\'myFieldName\'].errors"> {{ error }} </div>
	                    </div>
		            </template>
                    <input type="radio" name="myFieldName" value="myValue" id="myFormId_myFieldName_myValue" class="form-check-input" aria-describedby="myFormId_myFieldName_errors" v-model="fields[\'myFieldName\'].value" v-bind:required="fields[\'myFieldName\'].isRequired" v-bind:disabled="fields[\'myFieldName\'].isDisabled" v-bind:aria-invalid="fieldHasError(\'myFieldName\')" />
                    <label class="form-check-label" for="myFormId_myFieldName_myValue">MyValue<template><sup v-if="fields[\'myFieldName\'].isRequired">*</sup></template></label>
                </div>
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::radio('myValue', 'myFieldName')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <div class="form-group custom-control custom-radio">
		            <template>           
	                    <div class="invalid-feedback d-block" role="alert" id="myFormId_myFieldName_errors" v-if="fieldHasError(\'myFieldName\')">
	                        <div v-for="error in fields[\'myFieldName\'].errors"> {{ error }} </div>
	                    </div>
		            </template>
                    <input type="radio" name="myFieldName" value="myValue" id="myFormId_myFieldName_myValue" class="form-check-input" required aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_myValue_helpText" v-model="fields[\'myFieldName\'].value" v-bind:required="fields[\'myFieldName\'].isRequired" v-bind:disabled="fields[\'myFieldName\'].isDisabled" v-bind:aria-invalid="fieldHasError(\'myFieldName\')" />
                    <label class="form-check-label" for="myFormId_myFieldName_myValue">MyValue<template><sup v-if="fields[\'myFieldName\'].isRequired">*</sup></template></label>
                    <small id="myFormId_myFieldName_myValue_helpText" class="text-muted form-text small">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4Vue;

class CheckboxInputTest extends AbstractBootstrap4VueTest
{

    public function testSimple()
    {
        $element = \Form::checkbox('myFieldName', 'myValue');

        $this->assertHtmlEquals(
            '
                <div class="form-group form-check" v-bind:class="{ \'has-error\': fieldHasError(\'myFieldName\') }">
		            <template>           
	                    <div role="alert" class="alert m-b-1 alert-danger" id="myFormId_myFieldName_errors" v-if="fieldHasError(\'myFieldName\')">
	                        <div v-for="error in fields[\'myFieldName\'].errors"> {{ error }} </div>
	                    </div>
		            </template>
                    <input type="checkbox" name="myFieldName" class="custom-control-input" value="myValue" id="myFormId_myFieldName"aria-describedby="myFormId_myFieldName_errors" v-model="fields[\'myFieldName\'].value" v-bind:required="fields[\'myFieldName\'].isRequired" v-bind:disabled="fields[\'myFieldName\'].isDisabled" v-bind:aria-invalid="fieldHasError(\'myFieldName\')" />
                    <label class="form-check-label" for="myFormId_myFieldName">MyFieldName<template><sup v-if="fields[\'myFieldName\'].isRequired">*</sup></template></label>
                </div>
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::checkbox('myFieldName', 'myValue')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <div class="form-group form-check" v-bind:class="{ \'has-error\': fieldHasError(\'myFieldName\') }">
		            <template>           
	                    <div role="alert" class="alert m-b-1 alert-danger" id="myFormId_myFieldName_errors" v-if="fieldHasError(\'myFieldName\')">
	                        <div v-for="error in fields[\'myFieldName\'].errors"> {{ error }} </div>
	                    </div>
		            </template>
                    <input type="checkbox" name="myFieldName" class="custom-control-input" value="myValue" id="myFormId_myFieldName" required aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" v-model="fields[\'myFieldName\'].value" v-bind:required="fields[\'myFieldName\'].isRequired" v-bind:disabled="fields[\'myFieldName\'].isDisabled" v-bind:aria-invalid="fieldHasError(\'myFieldName\')" />
                    <label class="form-check-label" for="myFormId_myFieldName">MyFieldName<template><sup v-if="fields[\'myFieldName\'].isRequired">*</sup></template></label>
                    <small id="myFormId_myFieldName_helpText" class="text-muted form-text small">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
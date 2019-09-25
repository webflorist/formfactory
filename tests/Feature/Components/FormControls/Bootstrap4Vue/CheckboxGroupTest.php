<?php

namespace FormFactoryTests\Feature\Components\FormControls\Bootstrap4Vue;

class CheckboxGroupTest extends AbstractBootstrap4VueTest
{

    public function testSimple()
    {
        $element = \Form::checkboxGroup([
            \Form::checkbox('myFirstCheckboxFieldName'),
            \Form::checkbox('mySecondCheckboxFieldName'),
        ]);

        $this->assertHtmlEquals(
            '
                <fieldset class="form-group">
		            <template>           
	                    <div class="invalid-feedback" role="alert" id="myFormId_myFirstCheckboxFieldName_errors" v-if="fieldHasError(\'myFirstCheckboxFieldName\')">
	                        <div v-for="error in fields[\'myFirstCheckboxFieldName\'].errors"> {{ error }} </div>
	                    </div>
		            </template>
		            <template>           
	                    <div class="invalid-feedback" role="alert" id="myFormId_mySecondCheckboxFieldName_errors" v-if="fieldHasError(\'mySecondCheckboxFieldName\')">
	                        <div v-for="error in fields[\'mySecondCheckboxFieldName\'].errors"> {{ error }} </div>
	                    </div>
		            </template>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="myFirstCheckboxFieldName" class="custom-control-input" value="1" id="myFormId_myFirstCheckboxFieldName" v-bind:aria-describedby="(fieldHasError(\'myFirstCheckboxFieldName\') ? \'myFormId_myFirstCheckboxFieldName_errors\' : \'\')" v-model="fields[\'myFirstCheckboxFieldName\'].value" v-bind:required="fields[\'myFirstCheckboxFieldName\'].isRequired" v-bind:disabled="fields[\'myFirstCheckboxFieldName\'].isDisabled" v-bind:class="{ \'is-invalid\': fieldHasError(\'myFirstCheckboxFieldName\') }" v-bind:aria-invalid="fieldHasError(\'myFirstCheckboxFieldName\')" />
                        <label class="custom-control-label" for="myFormId_myFirstCheckboxFieldName">MyFirstCheckboxFieldName<template><sup v-if="fields[\'myFirstCheckboxFieldName\'].isRequired">*</sup></template></label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="mySecondCheckboxFieldName" class="custom-control-input" value="1" id="myFormId_mySecondCheckboxFieldName" v-bind:aria-describedby="(fieldHasError(\'mySecondCheckboxFieldName\') ? \'myFormId_mySecondCheckboxFieldName_errors\' : \'\')" v-model="fields[\'mySecondCheckboxFieldName\'].value" v-bind:required="fields[\'mySecondCheckboxFieldName\'].isRequired" v-bind:disabled="fields[\'mySecondCheckboxFieldName\'].isDisabled" v-bind:class="{ \'is-invalid\': fieldHasError(\'mySecondCheckboxFieldName\') }" v-bind:aria-invalid="fieldHasError(\'mySecondCheckboxFieldName\')" />
                        <label class="custom-control-label" for="myFormId_mySecondCheckboxFieldName">MySecondCheckboxFieldName<template><sup v-if="fields[\'mySecondCheckboxFieldName\'].isRequired">*</sup></template></label>
                    </div>
                </fieldset>
            ',
            $element->generate()
        );
    }


    public function testComplex()
    {
        $element = \Form::checkboxGroup([
            \Form::checkbox('myFirstCheckboxFieldName')
                ->helpText('myFirstCheckboxHelpText')
                ->errors(['myFirstCheckboxFirstError', 'myFirstCheckboxSecondError']),
            \Form::checkbox('mySecondCheckboxFieldName')
                ->helpText('mySecondCheckboxHelpText')
                ->errors(['mySecondCheckboxFirstError', 'mySecondCheckboxSecondError'])
                ->rules('required'),
        ]);

        $this->assertHtmlEquals(
            '
                <fieldset class="form-group">
		            <template>           
	                    <div class="invalid-feedback" role="alert" id="myFormId_myFirstCheckboxFieldName_errors" v-if="fieldHasError(\'myFirstCheckboxFieldName\')">
	                        <div v-for="error in fields[\'myFirstCheckboxFieldName\'].errors"> {{ error }} </div>
	                    </div>
		            </template>
		            <template>           
	                    <div class="invalid-feedback" role="alert" id="myFormId_mySecondCheckboxFieldName_errors" v-if="fieldHasError(\'mySecondCheckboxFieldName\')">
	                        <div v-for="error in fields[\'mySecondCheckboxFieldName\'].errors"> {{ error }} </div>
	                    </div>
		            </template>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="myFirstCheckboxFieldName" class="custom-control-input" value="1" id="myFormId_myFirstCheckboxFieldName" v-bind:aria-describedby="(fieldHasError(\'myFirstCheckboxFieldName\') ? \'myFormId_myFirstCheckboxFieldName_errors\' : \'\')+ \' myFormId_myFirstCheckboxFieldName_helpText\'" v-model="fields[\'myFirstCheckboxFieldName\'].value" v-bind:required="fields[\'myFirstCheckboxFieldName\'].isRequired" v-bind:disabled="fields[\'myFirstCheckboxFieldName\'].isDisabled" v-bind:class="{ \'is-invalid\': fieldHasError(\'myFirstCheckboxFieldName\') }" v-bind:aria-invalid="fieldHasError(\'myFirstCheckboxFieldName\')" />
                        <label class="custom-control-label" for="myFormId_myFirstCheckboxFieldName">MyFirstCheckboxFieldName<template><sup v-if="fields[\'myFirstCheckboxFieldName\'].isRequired">*</sup></template></label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="mySecondCheckboxFieldName" class="custom-control-input" value="1" id="myFormId_mySecondCheckboxFieldName" v-bind:aria-describedby="(fieldHasError(\'mySecondCheckboxFieldName\') ? \'myFormId_mySecondCheckboxFieldName_errors\' : \'\')+ \' myFormId_mySecondCheckboxFieldName_helpText\'" v-model="fields[\'mySecondCheckboxFieldName\'].value"v-bind:required="fields[\'mySecondCheckboxFieldName\'].isRequired" v-bind:disabled="fields[\'mySecondCheckboxFieldName\'].isDisabled" v-bind:class="{ \'is-invalid\': fieldHasError(\'mySecondCheckboxFieldName\') }" v-bind:aria-invalid="fieldHasError(\'mySecondCheckboxFieldName\')" />
                        <label class="custom-control-label" for="myFormId_mySecondCheckboxFieldName">MySecondCheckboxFieldName<template><sup v-if="fields[\'mySecondCheckboxFieldName\'].isRequired">*</sup></template></label>
                    </div>
                    <small id="myFormId_myFirstCheckboxFieldName_helpText" class="text-muted form-text small">myFirstCheckboxHelpText</small>
                    <small id="myFormId_mySecondCheckboxFieldName_helpText" class="text-muted form-text small">mySecondCheckboxHelpText</small>
                </fieldset>
            ',
            $element->generate()
        );
    }


}
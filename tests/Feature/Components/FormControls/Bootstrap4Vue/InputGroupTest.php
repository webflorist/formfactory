<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4Vue;

class InputGroupTest extends AbstractBootstrap4VueTest
{

    public function testSimpleInputGroup()
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
                <div class="form-group" v-bind:class="{ \'has-error\': fieldHasError(\'myTextFieldName\') }">
                    <label for="myFormId_myTextFieldName">MyTextFieldName<template><sup v-if="fields[\'myTextFieldName\'].isRequired">*</sup></template></label>
		            <template>           
	                    <div role="alert" class="alert m-b-1 alert-danger" id="myFormId_myTextFieldName_errors" v-if="fieldHasError(\'myTextFieldName\')">
	                        <div v-for="error in fields[\'myTextFieldName\'].errors"> {{ error }} </div>
	                    </div>
		            </template>
		            <template>           
	                    <div role="alert" class="alert m-b-1 alert-danger" id="myFormId_mySelectFieldName_errors" v-if="fieldHasError(\'mySelectFieldName\')">
	                        <div v-for="error in fields[\'mySelectFieldName\'].errors"> {{ error }} </div>
	                    </div>
		            </template>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">text-before</span>
                        </div>
                        <input type="text" name="myTextFieldName" class="form-control" id="myFormId_myTextFieldName" placeholder="MyTextFieldName" aria-describedby="myFormId_myTextFieldName_errors" v-model="fields[\'myTextFieldName\'].value" v-bind:required="fields[\'myTextFieldName\'].isRequired" v-bind:disabled="fields[\'myTextFieldName\'].isDisabled" v-bind:aria-invalid="fieldHasError(\'myTextFieldName\')" />
                        <div class="input-group-append">
                            <select name="mySelectFieldName" id="myFormId_mySelectFieldName" aria-describedby="myFormId_mySelectFieldName_errors" v-model="fields[\'mySelectFieldName\'].value" v-bind:required="fields[\'mySelectFieldName\'].isRequired" v-bind:disabled="fields[\'mySelectFieldName\'].isDisabled" v-bind:aria-invalid="fieldHasError(\'mySelectFieldName\')" class="form-control">
                                <option value="myOptionValue1">myOptionValue1</option>
                                <option value="myOptionValue2">myOptionValue2</option>
                            </select>
                            <button type="submit" name="submit" class="btn btn-primary" id="myFormId_submit">Submit</button>
                        </div>
                    </div>
                </div>
            ',
            $element->generate()
        );
    }

}
<?php

namespace FormFactoryTests\Feature\Components\FormControls\Bootstrap4Vue;

class SelectTest extends AbstractBootstrap4VueTest
{

    public function testSimple()
    {
        $element = \Form::select('myFieldName');

        $this->assertHtmlEquals(
            '
                <div class="form-group" v-bind:class="{ \'has-error\': fieldHasError(\'myFieldName\') }">
                    <label for="myFormId_myFieldName">MyFieldName<template><sup v-if="fields[\'myFieldName\'].isRequired">*</sup></template></label>
		            <template>           
	                    <div role="alert" class="alert m-b-1 alert-danger" id="myFormId_myFieldName_errors" v-if="fieldHasError(\'myFieldName\')">
	                        <div v-for="error in fields[\'myFieldName\'].errors"> {{ error }} </div>
	                    </div>
		            </template>
                    <select name="myFieldName" class="form-control" id="myFormId_myFieldName" aria-describedby="myFormId_myFieldName_errors" v-model="fields[\'myFieldName\'].value" v-bind:required="fields[\'myFieldName\'].isRequired" v-bind:disabled="fields[\'myFieldName\'].isDisabled" v-bind:aria-invalid="fieldHasError(\'myFieldName\')"></select>
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
                <div class="form-group" v-bind:class="{ \'has-error\': fieldHasError(\'myFieldName\') }">
                    <label for="myFormId_myFieldName">MyFieldName<template><sup v-if="fields[\'myFieldName\'].isRequired">*</sup></template></label>
		            <template>           
	                    <div role="alert" class="alert m-b-1 alert-danger" id="myFormId_myFieldName_errors" v-if="fieldHasError(\'myFieldName\')">
	                        <div v-for="error in fields[\'myFieldName\'].errors"> {{ error }} </div>
	                    </div>
		            </template>
                    <select name="myFieldName" class="form-control" id="myFormId_myFieldName" required aria-describedby="myFormId_myFieldName_errors myFormId_myFieldName_helpText" v-model="fields[\'myFieldName\'].value" v-bind:required="fields[\'myFieldName\'].isRequired" v-bind:disabled="fields[\'myFieldName\'].isDisabled" v-bind:aria-invalid="fieldHasError(\'myFieldName\')" >
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
                    <small id="myFormId_myFieldName_helpText" class="text-muted form-text small">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}
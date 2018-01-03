@extends('master')
@section('content')
    {!! Form::open('myFormId')->requestObject(FormBuilderTests\Browser\Requests\AjaxValidationTestRequest::class)->action('/ajaxvalidation-post')->ajaxValidation(true)->novalidate() !!}
    {!! Form::text('text') !!}
    {!! Form::submit('submit') !!}
    {!! Form::close() !!}
@endsection
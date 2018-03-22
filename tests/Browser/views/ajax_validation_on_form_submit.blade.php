@extends('master')
@section('content')
    {!! Form::open('myFormId')->requestObject(FormFactoryTests\Browser\Requests\AjaxValidationTestRequest::class)->action('/ajaxvalidation-post')->ajaxValidation(true)->novalidate() !!}
    {!! Form::text('text') !!}
    {!! Form::submit('submit') !!}
    {!! Form::close() !!}
@endsection
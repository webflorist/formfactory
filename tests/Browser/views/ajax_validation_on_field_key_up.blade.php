@extends('master')
@section('content')
    {!! Form::open('myFormId')->requestObject(FormFactoryTests\Browser\Requests\AjaxValidationTestRequest::class)->action('/ajaxvalidation-post')->novalidate() !!}
    {!! Form::text('text')->ajaxValidation('onKeyup') !!}
    {!! Form::submit('submit') !!}
    {!! Form::close() !!}
@endsection
@extends('master')
@section('content')
    {!! Form::open('myFormId')->requestObject(FormBuilderTests\Browser\Requests\CaptchaTestRequest::class)->action('/captcha-post') !!}
    {!! Form::submit('submit') !!}
    {!! Form::close() !!}
@endsection
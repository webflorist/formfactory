@extends('master')
@section('content')
    {!! Form::open('myFormId')->requestObject(HtmlBuilderTests\Browser\Requests\CaptchaTestRequest::class)->action('/captcha-post') !!}
    {!! Form::submit('submit') !!}
    {!! Form::close() !!}
@endsection
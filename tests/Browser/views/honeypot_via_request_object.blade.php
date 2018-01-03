@extends('master')
@section('content')
    {!! Form::open('myFormId')->requestObject(HtmlBuilderTests\Browser\Requests\HoneypotTestRequest::class)->action('/honeypot-post') !!}
    {!! Form::submit('submit') !!}
    {!! Form::close() !!}
@endsection
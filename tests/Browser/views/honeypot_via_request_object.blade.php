@extends('master')
@section('content')
    {!! Form::open('myFormId')->requestObject(FormBuilderTests\Browser\Requests\HoneypotTestRequest::class)->action('/honeypot-post') !!}
    {!! Form::submit('submit') !!}
    {!! Form::close() !!}
@endsection
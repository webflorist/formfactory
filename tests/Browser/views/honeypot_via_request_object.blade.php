@extends('master')
@section('content')
    {!! Form::open('myFormId')->requestObject(FormFactoryTests\Browser\Requests\HoneypotTestRequest::class)->action('/honeypot-post') !!}
    {!! Form::submit('submit') !!}
    {!! Form::close() !!}
@endsection
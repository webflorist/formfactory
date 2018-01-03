@extends('master')
@section('content')
    {!! Form::open('myFormId')->requestObject(FormBuilderTests\Browser\Requests\TimeLimitTestRequest::class)->action('/timelimit-post') !!}
    {!! Form::submit('submit') !!}
    {!! Form::close() !!}
@endsection
@extends('master')
@section('content')
    {!! Form::open('myFormId')->requestObject(FormFactoryTests\Browser\Requests\TimeLimitTestRequest::class)->action('/timelimit-post') !!}
    {!! Form::submit('submit') !!}
    {!! Form::close() !!}
@endsection
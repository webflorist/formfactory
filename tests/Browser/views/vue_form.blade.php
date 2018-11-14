@extends('master')
@section('content')
    {!! Form::vOpen('myFormId')->requestObject(FormFactoryTests\Browser\Requests\VueFormTestRequest::class)->action($action) !!}
    {!! Form::text('myTextField') !!}
    {!! Form::submit('submit') !!}
    {!! Form::close() !!}
@endsection
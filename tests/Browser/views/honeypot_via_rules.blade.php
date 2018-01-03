@extends('master')
@section('content')
    {!! Form::open('myFormId')->rules([ '_honeypot' => 'honeypot'])->action('/honeypot-post') !!}
    {!! Form::submit('submit') !!}
    {!! Form::close() !!}
@endsection
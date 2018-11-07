@extends('master')
@section('content')
    {!! Form::open('myFormId')->requestObject(FormFactoryTests\Browser\Requests\DynamicListsTestRequest::class)->action('/dynamic-lists-post')->values($values??[])->novalidate() !!}
    {!! Form::dynamicList(
            'outer_dynamic_list',
            Form::panel()->content([
                Form::text('outer_dynamic_list[][text]'),
                Form::dynamicList(
                    'outer_dynamic_list[][inner_dynamic_list]',
                    Form::inputGroup()->content(Form::text('outer_dynamic_list[][inner_dynamic_list][]'))
                )
            ])
        ) !!}
    {!! Form::submit('submit') !!}
    {!! Form::close() !!}
@endsection
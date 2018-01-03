@extends('master')
@section('content')
    {!! Form::open('myFormId')->requestObject(HtmlBuilderTests\Browser\Requests\DynamicListsTestRequest::class)->action('/dynamic-lists-post')->values($values??[])->novalidate() !!}
    {!! Form::fieldset()->dynamicList(
            'outer_dynamic_list',
            Form::panel([
                Form::text('outer_dynamic_list[][text]'),
                Form::fieldset()->dynamicList(
                    'outer_dynamic_list[][inner_dynamic_list]',
                    Form::inputGroup('inner_dynamic_list',[
                        Form::text('outer_dynamic_list[][inner_dynamic_list][]'),
                    ])
                )
            ])
        ) !!}
    {!! Form::submit('submit') !!}
    {!! Form::close() !!}
@endsection
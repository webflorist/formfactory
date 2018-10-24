@include('formfactory::raw._general.errors')

<label>
    {!! $el->renderHtml() !!}
    @include('formfactory::raw._general.label-text')
</label>

@include('formfactory::raw._general.help-text')
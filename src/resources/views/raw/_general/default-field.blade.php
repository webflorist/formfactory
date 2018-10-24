@if($el->label)
    <label for="{{$el->attributes->id}}">@include('formfactory::raw._general.label-text')</label>
@endif

@include('formfactory::raw._general.errors')

{!! $el->renderHtml() !!}

 @include('formfactory::raw._general.help-text')
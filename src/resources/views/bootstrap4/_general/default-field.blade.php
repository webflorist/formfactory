<div class="form-group{{ ($el->errors) ? ' has-error' : '' }}">

    @if($el->label)
        <label for="{{$el->attributes->id}}">@include('formfactory::bootstrap4._general.label-text')</label>
    @endif

    @include('formfactory::bootstrap4._general.errors')

    {!! $el->renderHtml() !!}

    @include('formfactory::bootstrap4._general.help-text')

</div>
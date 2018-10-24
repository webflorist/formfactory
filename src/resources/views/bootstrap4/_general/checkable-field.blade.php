<div class="form-group form-check{{ ($el->errors) ? ' has-error' : '' }}">

    @include('formfactory::bootstrap4._general.errors')

    {!! $el->renderHtml() !!}

    @if($el->label)
        <label for="{{$el->attributes->id}}">@include('formfactory::bootstrap4._general.label-text')</label>
    @endif

    @include('formfactory::bootstrap4._general.help-text')

</div>
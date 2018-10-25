<div class="{{ $class ?? '' }}" v-bind:class="{ 'has-error': fieldHasError('{{$el->attributes->name}}') }">

    @section('field_content')

    @show

    @if($el->helpText)
        <small id="{{$el->attributes->id}}_helpText" class="form-text text-muted">{{$el->helpText}}</small>
    @endif

</div>
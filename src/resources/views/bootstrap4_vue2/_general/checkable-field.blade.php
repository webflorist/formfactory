<div class="form-group form-check{{ ($el->isInline() ? ' form-check-inline' : '') }}" v-bind:class="{ 'has-error': fieldHasError('{{$el->attributes->name}}') }">

    @if($el->errors !== false)
        @include('formfactory::bootstrap4_vue2._general.errors', ['fieldName' => $el->attributes->name, 'fieldId' => $el->attributes->id])
    @endif

    {!! $el->renderHtml() !!}

    @include('formfactory::bootstrap4_vue2._general.label', ['class' => 'form-check-label'])

    @include('formfactory::bootstrap4_vue2._general.help-text')

</div>
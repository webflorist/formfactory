<div class="form-group form-check{{ ($el->isInline() ? ' form-check-inline' : '') }}" v-bind:class="{ 'has-error': fieldHasError('{{$el->attributes->name}}') }">

    @include('formfactory::bootstrap4_vue2._general.errors')

    {!! $el->renderHtml() !!}

    @include('formfactory::bootstrap4_vue2._general.label', ['class' => 'form-check-label'])

    @include('formfactory::bootstrap4_vue2._general.help-text')

</div>
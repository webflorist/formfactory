<div class="form-group" v-bind:class="{ 'has-error': fieldHasError('{{$el->attributes->name}}') }">

    @include('formfactory::bootstrap4_vue2._general.label')

    @include('formfactory::bootstrap4_vue2._general.errors')

    {!! $el->renderHtml() !!}

    @include('formfactory::bootstrap4_vue2._general.help-text')

</div>
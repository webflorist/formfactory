<div class="form-group" v-bind:class="{ 'has-error': fieldHasError('{{$el->getFieldName()}}') }">

    @if($el->label->hasLabel() && $el->label->displayLabel)
        @include('formfactory::bootstrap4_vue2._general.label')
    @endif

    @if($el->errors->displayErrors)
        @include('formfactory::bootstrap4_vue2._general.errors', ['fieldName' => $el->attributes->name])
    @endif

    {!! $el->renderHtml() !!}

    @if($el->helpText->hasHelpText() && $el->helpText->displayHelpText)
        @include('formfactory::bootstrap4_vue2._general.help-text')
    @endif

</div>
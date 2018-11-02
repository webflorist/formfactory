<div class="form-group form-check{{ ($el->isInline() ? ' form-check-inline' : '') }}" v-bind:class="{ 'has-error': fieldHasError('{{$el->getFieldName()}}') }">

    @if($el->errors->displayErrors)
        @include('formfactory::bootstrap4_vue2._general.errors', ['fieldName' => $el->getFieldName()])
    @endif

    {!! $el->renderHtml() !!}

    @if($el->label->hasLabel() && $el->label->displayLabel)
        @include('formfactory::bootstrap4_vue2._general.label', ['class' => 'form-check-label'])
    @endif

    @if($el->helpText->hasHelpText() && $el->helpText->displayHelpText)
        @include('formfactory::bootstrap4_vue2._general.help-text')
    @endif

</div>
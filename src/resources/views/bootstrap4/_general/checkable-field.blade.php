<div class="form-group form-check{{ ($el->isInline() ? ' form-check-inline' : '') }}{{ ($el->errors->hasErrors()) ? ' has-error' : '' }}">

    @if($el->errors->hasErrors() && $el->errors->displayErrors)
        @include('formfactory::bootstrap4._general.errors')
    @endif

    {!! $el->renderHtml() !!}

    @if($el->label->hasLabel() && $el->label->displayLabel)
        @include('formfactory::bootstrap4._general.label', ['class' => 'form-check-label'])
    @endif

    @if($el->helpText->hasHelpText() && $el->helpText->displayHelpText)
        @include('formfactory::bootstrap4._general.help-text')
    @endif

</div>
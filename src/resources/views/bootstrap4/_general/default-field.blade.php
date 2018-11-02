<div class="form-group{{ ($el->errors->hasErrors()) ? ' has-error' : '' }}">

    @if($el->label->hasLabel() && $el->label->displayLabel)
        @include('formfactory::bootstrap4._general.label')
    @endif

    @if($el->errors->hasErrors() && $el->errors->displayErrors)
        @include('formfactory::bootstrap4._general.errors')
    @endif

    {!! $el->renderHtml() !!}

    @if($el->helpText->hasHelpText() && $el->helpText->displayHelpText)
        @include('formfactory::bootstrap4._general.help-text')
    @endif

</div>
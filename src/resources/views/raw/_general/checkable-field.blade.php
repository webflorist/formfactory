@if($el->errors->hasErrors() && $el->errors->displayErrors)
    @include('formfactory::raw._general.errors', [
        'errors' => $el->errors->getErrors(),
        'containerId' => $el->errors->getContainerId()
    ])
@endif

{!! $el->renderHtml() !!}

@if($el->label->hasLabel() && $el->label->displayLabel)
    @include('formfactory::raw._general.label',[
        'fieldId' => $el->attributes->id,
        'labelText' => $el->label->getText(),
        'showRequiredFieldIndicator' => $el->attributes->required && $el->label->displayRequiredFieldIndicator
    ])
@endif

@if($el->helpText->hasHelpText() && $el->helpText->displayHelpText)
    @include('formfactory::raw._general.help-text', [
        'helpText' => $el->helpText->getText(),
        'containerId' => $el->helpText->getContainerId()
    ])
@endif
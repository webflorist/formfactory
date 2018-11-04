<fieldset{!! $el->attributes->render(true) !!}>

    @if($el->legend)
        <legend>
            {!! $el->legend !!}
            @if($el->radioInputs[0]->attributes->required)
                @include('formfactory::raw._general.required-field-indicator', ['fieldName' => $el->radioName])
            @endif
        </legend>
    @endif

    @if($el->errors->hasErrors())
            @include('formfactory::raw._general.errors', [
                'errors' => $el->errors->getErrors(),
                'containerId' => $el->errors->getContainerId()
            ])
    @endif

    {!! $el->generateContent() !!}

    @if($el->helpText->hasHelpText() && $el->helpText->displayHelpText)
        @include('formfactory::raw._general.help-text', [
            'helpText' => $el->helpText->getText(),
            'containerId' => $el->helpText->getContainerId()
        ])
    @endif

</fieldset>
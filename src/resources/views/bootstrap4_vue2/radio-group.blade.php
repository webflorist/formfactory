<fieldset{!! $el->attributes->render(true) !!}>
    @if($el->legend)
        <legend>
            {!! $el->legend !!}
            @include('formfactory::bootstrap4_vue2._general.required-field-indicator', ['fieldName' => $el->radioName])
        </legend>
    @endif
    @include('formfactory::bootstrap4_vue2._general.errors', ['fieldName' => $el->radioName, 'fieldId' => $el->radioInputs[0]->attributes->id])
    {!! $el->generateContent() !!}
</fieldset>
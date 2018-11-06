<fieldset{!! $el->attributes->render(true) !!}>

    {!! $el->legend !!}

    {!! $el->errors !!}

    {!! $el->generateContent() !!}

    {!! $el->helpText !!}

</fieldset>
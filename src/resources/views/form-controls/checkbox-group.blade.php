{!! $el->renderStartTag() !!}

    {!! $el->legend !!}

    @foreach($el->containedErrors as $error)
        {!! $error !!}
    @endforeach

    {!! $el->generateContent() !!}

    @foreach($el->containedHelpTexts as $helpText)
        {!! $helpText !!}
    @endforeach

{!! $el->renderEndTag() !!}
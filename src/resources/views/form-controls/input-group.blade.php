{!! $el->label !!}

@foreach($el->containedErrors as $error)
    {!! $error !!}
@endforeach

{!! $el->renderHtml() !!}

@foreach($el->containedHelpTexts as $helpText)
    {!! $helpText !!}
@endforeach
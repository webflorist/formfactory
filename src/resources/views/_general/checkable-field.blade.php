{!! $el->errors !!}

@if($el->label->wrapCheckable)
<label>{!! $el->renderHtml() !!}{!! $el->label->generateContent() !!}</label>
@else
{!! $el->renderHtml() !!}
{!! $el->label !!}
@endif

{!! $el->helpText !!}
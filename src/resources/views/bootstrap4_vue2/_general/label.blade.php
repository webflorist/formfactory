@php
    if (!isset($includeRequiredFieldIndicator)) {
        $includeRequiredFieldIndicator = true;
    }
@endphp

@if($el->label)
    <label{!! (!empty($class) ? ' class="'.$class.'"' : '') !!} for="{{$el->attributes->id}}">
        {{$el->label}}
        @if($includeRequiredFieldIndicator)
            @include('formfactory::bootstrap4_vue2._general.required-field-indicator', ['fieldName' => $el->attributes->name])
        @endif
    </label>
@endif
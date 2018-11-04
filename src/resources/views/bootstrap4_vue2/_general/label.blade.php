<label{!! (!empty($class) ? ' class="'.$class.'"' : '') !!} for="{{$el->attributes->id}}">
    {{$el->label}}
    @if($el->label->displayRequiredFieldIndicator)
        @include('formfactory::bootstrap4_vue2._general.required-field-indicator', ['fieldName' => $el->attributes->name])
    @endif
</label>
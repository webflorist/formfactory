<label{!! (!empty($class) ? ' class="'.$class.'"' : '') !!} for="{{$el->attributes->id}}">
    {{$el->label}}
    @if($el->attributes->required && $el->label->displayRequiredFieldIndicator)
        <sup>*</sup>
    @endif
</label>
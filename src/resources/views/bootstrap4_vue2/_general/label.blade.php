@if($el->label)
    <label{!! !empty($class) ? ' class="'.$class.'"' : '' !!} for="{{$el->attributes->id}}">{{$el->label}}@if($el->attributes->required)<sup v-if="fields['{{$el->attributes->name}}'].isRequired">*</sup>@endif</label>
@endif
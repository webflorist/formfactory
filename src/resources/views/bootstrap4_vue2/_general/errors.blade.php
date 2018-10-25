<div role="alert" class="alert m-b-1 alert-danger" id="{{$el->attributes->id}}_errors" v-if="fieldHasError('{{$el->attributes->name}}')">
    <div v-for="error in fields['{{$el->attributes->name}}'].errors">@{{ error }}</div>
</div>
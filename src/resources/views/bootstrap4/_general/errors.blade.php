<div role="alert" class="alert m-b-1 alert-danger" id="{{$el->getFieldName()}}_errors">
    @foreach($el->errors->getErrors() as $error)
        <div>{{$error}}</div>
    @endforeach
</div>
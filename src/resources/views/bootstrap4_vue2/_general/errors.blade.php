@if($el->errors)
    <div id="{{$el->attributes->id}}_errors">
        @foreach($el->errors->getErrors() as $error)
            <div>{{$error}}</div>
        @endforeach
    </div>
@endif
<div class="form-group form-check" v-bind:class="{ 'has-error': 'fields.{{\Nicat\FormFactory\Utilities\FormFactoryTools::convertArrayFieldHtmlName2JsNotation($el->attributes->name)}}.errors.length' }">

    @include('formfactory::bootstrap4_vue2._general.errors')

    {!! $el->renderHtml() !!}

    @if($el->label)
        <label for="{{$el->attributes->id}}">@include('formfactory::bootstrap4_vue2._general.label-text')</label>
    @endif

    @include('formfactory::bootstrap4_vue2._general.help-text')

</div>
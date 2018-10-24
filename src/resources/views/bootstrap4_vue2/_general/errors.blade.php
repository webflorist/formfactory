@if($el->errors)
    <div role="alert" class="alert m-b-1 alert-danger" id="{{$el->attributes->id}}_errors" v-if="fields.{{\Nicat\FormFactory\Utilities\FormFactoryTools::convertArrayFieldHtmlName2JsNotation($el->attributes->name)}}.errors.length">
        <div v-for="error in fields.{{\Nicat\FormFactory\Utilities\FormFactoryTools::convertArrayFieldHtmlName2JsNotation($el->attributes->name)}}.errors">
            @{{ error }}
        </div>
    </div>
@endif
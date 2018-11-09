<?php

namespace FormFactoryTests\Feature\Components\FormControls;

use FormFactoryTests\TestCase;
use HtmlFactoryTests\Traits\AssertsHtml;

class VueTest extends TestCase
{
    protected $openForm = false;
    protected $closeForm = false;
    protected $decorators = ['bootstrap:v4'];
    protected $enableVue = true;

    public function test_simple_vue_app()
    {
        \Form::open('myFormId');

        \Form::text('text')->generate();

        \Form::close();

        $vue = \Form::vue('myFormId')->generate();

        $this->assertEquals(
            'newVue({"el":"#myFormId","data":{"fields":{"text":{"value":"","isRequired":false,"isDisabled":false,"errors":[]}},"lang":{"general_form_error":"Theformcouldnotbesubmittedsuccessfully.Pleasetryagainlater.","form_timeout_error":"Theformhasexpired.Pleasereloadthepage."},"isSubmitting":false,"generalErrors":[],"captchaQuestion":null},"methods":{"fieldHasError":function(fieldName){returnthis.fields[fieldName].errors.length>0;},"submitForm":function(){if(this.isSubmitting==false){this.isSubmitting=true;this.clearErrors();Axios.post(this.$el.getAttribute("action"),newFormData(this.$el)).then((response)=>{if(response.data.message){this.displaySuccessMessage(response.data.message);}if(response.data.reset_form){this.resetForm();}this.finishSubmit(response);}).catch((error)=>{if(error.response.status==422){for(letfieldNameinerror.response.data.errors){if(typeofthis.fields[fieldName]==="undefined"){this.generalErrors=this.generalErrors.concat(error.response.data.errors[fieldName]);}this.fields[fieldName].errors=error.response.data.errors[fieldName];}}elseif(error.response.status==419){this.generalErrors=[this.lang["form_expired_error"]];}else{this.generalErrors=[this.lang["general_form_error"]];}this.finishSubmit(error.response);});}},"clearErrors":function(){for(letfieldNameinthis.fields){this.fields[fieldName].errors=[];}this.generalErrors=[];},"resetForm":function(){for(letfieldNameinthis.fields){this.fields[fieldName].errors=[];this.fields[fieldName].value="";}this.generalErrors=[];},"finishSubmit":function(response){this.isSubmitting=false;if(response.data.captcha_question){this.captchaQuestion=response.data.captcha_question;}},"displaySuccessMessage":function(message){Alert.createAlert(message,null,"success",1);}},"computed":{"hasErrors":function(){returnthis.fields[\'text\'].errors.length>0;}}});',
            str_replace(["\n"," "], '', $vue)
        );
    }


}
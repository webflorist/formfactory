<?php

namespace Webflorist\FormFactory\Vue;

use stdClass;
use Webflorist\FormFactory\Components\Form\Form;
use Webflorist\FormFactory\Components\Form\VueForm;
use Webflorist\VueFactory\VueInstance;

/**
 * Generates a vue instance from a Form.
 *
 * Class VueInstanceGenerator
 * @package Webflorist\FormFactory
 */
class VueInstanceGenerator
{

    /**
     * The Form to be vueified.
     *
     * @var Form
     */
    private $form;

    /**
     * The VueInstance we will build.
     *
     * @var VueInstance
     */
    private $vueInstance;

    /**
     * The "fields" data-object.
     *
     * @var stdClass
     */
    private $fieldData;

    /**
     * Form constructor.
     *
     * @param VueForm $form
     */
    public function __construct(VueForm $form)
    {
        $this->form = $form;
        $this->vueInstance = new VueInstance('#' . $this->form->getId());
        $this->fieldData = new stdClass();
        $this->parseFormControls();

        $this->vueInstance->addData('fields', $this->fieldData);
        $this->vueInstance->addMethod('fieldHasError', 'function (fieldName) {return this.fields[fieldName].errors.length > 0;}');
        $this->vueInstance->addMethod('fieldHasValue', 'function (fieldName) {return this.fields[fieldName].value ? true : false;}');
        $this->addComputeErrorFlags();

        $this->vueInstance->addData('lang', $this->getLangObject());

        $this->addSubmitFunctionality();
        $this->vueInstance->addMethod('handleFileInputChange', 'function (fieldName, event) {
            var files = event.target.files || event.dataTransfer.files;
              if (files) {
                if(files[0].type.match(/^image\/(gif|png|jpeg|svg\+xml)$/) || files[0].name.match(/\.(gif|png|jpe?g|svg)$/i)) {
                    var reader = new FileReader();
                    reader.onload = e => this.fields[fieldName].value = e.target.result;   
                    reader.readAsDataURL(files[0]);
                }
                else {
                    this.fields[fieldName].value = files[0].name;
                }
              }
        }');

    }

    /**
     * Parses form-controls and adds structured field-info to $this->fieldData.
     */
    private function parseFormControls()
    {
        foreach ($this->form->getFormControls() as $control) {
            if ($control->attributes->isSet('name')) {
                $this->fieldData->{$control->attributes->name} = new Field($control, $this->fieldData);
            }
        }
    }

    /**
     * Returns the VueInstance.
     *
     * @return VueInstance
     */
    public function getVueInstance(): VueInstance
    {
        return $this->vueInstance;
    }

    /**
     * Adds computed boolean properties for each flag.
     * indicating a current error in any field.
     */
    private function addComputeErrorFlags()
    {
        $jsStatements = [];
        foreach ($this->fieldData as $fieldName => $field) {
            $jsStatements[] = "this.fields['$fieldName'].errors.length > 0";
        }
        $jsStatements = implode(' || ', $jsStatements);

        $this->vueInstance->addComputed('hasErrors', "function () {return $jsStatements;}");
    }

    /**
     * Gets the lang object for various translated strings.
     *
     * @return stdClass
     */
    private function getLangObject()
    {
        $lang = new stdClass();

        $langKeys = [
            'general_form_error',
            'form_expired_error'
        ];

        foreach ($langKeys as $langKey) {
            $lang->{$langKey} = trans("webflorist-formfactory::formfactory.$langKey");
        }

        return $lang;
    }

    /**
     * Adds form-submission via Ajax.
     */
    private function addSubmitFunctionality()
    {
        $this->vueInstance->addMethod('submitForm', 'function() {
                    if (this.isSubmitting == false) {
                        this.isSubmitting = true;
                        this.clearErrors();
                        this.successMessage = "";
                        axios.post(
                            this.$el.getAttribute("action"),
                            new FormData(this.$el)
                        ).then((response) => {
                            if (response.data.redirect) {
                                this.redirect(response.data.redirect["url"], response.data.redirect["delay"]);
                            }
                            if (response.data.message) {
                                this.displaySuccessMessage(response.data.message);
                            }
                            if (response.data.reset_form) {
                                this.resetForm();
                            }
                            this.finishSubmit(response);
                        }).catch((error) => {
                            if(error.response.status == 422 || error.response.status == 429) {
                                for (let fieldName in error.response.data.errors) {
                                    if (typeof this.fields[fieldName] === "undefined") {
                                        this.generalErrors = this.generalErrors.concat(error.response.data.errors[fieldName]);
                                    }
                                    else {
                                        this.fields[fieldName].errors = error.response.data.errors[fieldName];
                                    }
                                }
                            }
                            else if (error.response.status == 419) {
                                this.handleCsrfTokenError(error.response);
                            }
                            else {
                                this.generalErrors = [this.lang["general_form_error"]];
                            }
                            this.finishSubmit(error.response);
                        });
                        

                    }
                }');

        $this->vueInstance->addMethod(
            'clearErrors',
            'function() {
                    for (let fieldName in this.fields) {
                        this.fields[fieldName].errors = [];
                    }
                    this.generalErrors = [];
                }');

        $this->vueInstance->addMethod(
            'resetForm',
            'function() {
                    for (let fieldName in this.fields) {
                        this.fields[fieldName].value = "";
                    }
                    this.clearErrors();
                }');

        $this->vueInstance->addMethod(
            'redirect',
            'async function(url, delay) {
                    await new Promise(resolve => setTimeout(resolve, delay));
                    window.location = url;
                }');

        $this->vueInstance->addMethod(
            'finishSubmit',
            'function(response) {
                    this.isSubmitting = false;
                    if (response.data.captcha_question) {
                        this.captchaQuestion = response.data.captcha_question;
                    }
                
                    // Scroll to first alert (error-message, succes, etc.)
                    this.$nextTick().then(() => {
                        let firstAlert = this.$el.querySelector("[role=alert]");
                        if (firstAlert !== null) {
                            $("html").animate({
                                scrollTop: $(firstAlert).offset().top - 150
                            }, "300");
                        }
                    });
                }');

        $this->vueInstance->addMethod(
            'displaySuccessMessage',
            config('formfactory.vue.methods.display_success_message')
        );

        $this->vueInstance->addMethod(
            'refreshCsrfToken',
            'function(submitAfterSuccess=false) {
                axios.get("/api/csrf-token").then((response) => {
                    axios.defaults.headers.common["X-CSRF-TOKEN"] = response.data;
                    if (submitAfterSuccess) {
                        this.submitForm();
                    }
                }).catch((error) => {
                    this.generalErrors = [this.lang["form_expired_error"]];
                });
            }'
        );

        $this->vueInstance->addMethod(
            'handleCsrfTokenError',
            'function(response) {
                    ' . $this->getFunctionBodyForHandlingCsrfTokenErrors() . '
                }'
        );

        $this->vueInstance->addData('isSubmitting', false);
        $this->vueInstance->addData('generalErrors', []);
        $this->vueInstance->addData('successMessage', []);
        $this->vueInstance->addData('captchaQuestion', $this->form->getCaptchaQuestion());
    }

    private function getFunctionBodyForHandlingCsrfTokenErrors()
    {
        if (config('formfactory.vue.auto_csrf_refresh')) {

            return '
                this.finishSubmit(response);
                this.refreshCsrfToken(true);
            ';

        }

        // Otherwise Vue should simply display an error.
        return 'this.generalErrors = [this.lang["form_expired_error"]];';
    }

}

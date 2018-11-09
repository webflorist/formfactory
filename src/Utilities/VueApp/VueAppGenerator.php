<?php

namespace Nicat\FormFactory\Utilities\VueApp;

use Nicat\FormFactory\Components\Form\Form;
use Nicat\VueFactory\VueInstance;
use stdClass;

/**
 * Generates a Vue-app from a Form.
 *
 * Class VueAppGenerator
 * @package Nicat\FormFactory
 */
class VueAppGenerator
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
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
        $this->vueInstance = new VueInstance('#' . $this->form->getId());
        $this->fieldData = new stdClass();
        $this->parseFormControls();

        $this->vueInstance->addData('fields', $this->fieldData);
        $this->vueInstance->addMethod('fieldHasError', 'function (fieldName) {return this.fields[fieldName].errors.length > 0;}');
        $this->addComputeErrorFlags();

        $this->vueInstance->addData('lang', $this->getLangObject());

        $this->addSubmitFunctionality();

    }

    /**
     * Parses form-controls and adds structured field-info to $this->fieldData.
     */
    private function parseFormControls()
    {
        foreach ($this->form->getFormControls() as $control) {
            if ($control->isAField()) {
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

        $lang->general_form_error = trans('Nicat-FormFactory::formfactory.general_form_error');
        $lang->form_timeout_error = trans('Nicat-FormFactory::formfactory.form_expired_error');

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
                        Axios.post(
                            this.$el.getAttribute("action"),
                            new FormData(this.$el)
                        ).then((response) => {
                            if (response.data.message) {
                                this.displaySuccessMessage(response.data.message);
                            }
                            if (response.data.reset_form) {
                                this.resetForm();
                            }
                            this.finishSubmit(response);
                        }).catch((error) => {
                            if(error.response.status == 422) {
                                for (let fieldName in error.response.data.errors) {
                                    if (typeof this.fields[fieldName] === "undefined") {
                                        this.generalErrors = this.generalErrors.concat(error.response.data.errors[fieldName]);
                                    }
                                    this.fields[fieldName].errors = error.response.data.errors[fieldName];
                                }
                            }
                            else if (error.response.status == 419) {
                                this.generalErrors = [this.lang["form_expired_error"]];
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
                        this.fields[fieldName].errors = [];
                        this.fields[fieldName].value = "";
                    }
                    this.generalErrors = [];
                }');

        $this->vueInstance->addMethod(
            'finishSubmit',
            'function(response) {
                    this.isSubmitting = false;
                    if (response.data.captcha_question) {
                        this.captchaQuestion = response.data.captcha_question;
                    }
                }');

        $this->vueInstance->addMethod(
            'displaySuccessMessage', config('formfactory.vue.methods.display_success_message'));

        $this->vueInstance->addData('isSubmitting', false);
        $this->vueInstance->addData('generalErrors', []);
        $this->vueInstance->addData('captchaQuestion', $this->form->getCaptchaQuestion());
    }

}
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
        $this->vueInstance->addMethod('fieldHasError', 'function (fieldName) {return this.errors.hasOwnProperty(fieldName);}');
        $this->vueInstance->addMethod('fieldHasValue', 'function (fieldName) {return this.fields[fieldName].value ? true : false;}');
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
     * Generates the JS for the VueInstance.
     *
     * @return string
     */
    public function generate() {
        $this->parseFormControls();
        $this->vueInstance->addData('fields', $this->fieldData);
        $this->addComputeErrorFlags();
        return $this->vueInstance->generate();
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
        $this->vueInstance->addComputed('hasErrors', "function () {return errors.length > 0;}");
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
            'form_expired_error',
            'unauthorized_error'
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
        $this->vueInstance->addMethod('submitForm', 'function(finishSubmitMethod=null) {
                    if (typeof finishSubmitMethod !== "string") {
                        finishSubmitMethod = "finishSubmit";
                    }
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
                            if (response.data.reloadPage) {
                                this.reloadPage(response.data.reloadPage["delay"]);
                            }
                            if (response.data.message) {
                                this.displaySuccessMessage(response.data.message);
                            }
                            if (response.data.reset_form) {
                                this.resetForm();
                            }
                            if (response.data.hide_form) {
                                this.hideForm = true;
                            }
                            this[finishSubmitMethod](response, true);
                        }).catch((error) => {
                            this.handleFieldErrors(error, "submitForm");
                            this[finishSubmitMethod](error.response, false);
                        });


                    }
                }');

        $this->vueInstance->addMethod(
            'handleFieldErrors',
            'function(error, callbackMethod) {
                if(error.response.status == 422 || error.response.status == 429) {
                    this.errors = error.response.data.errors;
                    for (let fieldName in error.response.data.errors) {
                        if (typeof this.fields[fieldName] === "undefined" && !fieldName.includes(".")) {
                            this.generalErrors = this.generalErrors.concat(error.response.data.errors[fieldName]);
                        }
                    }
                }
                else if (error.response.status == 401) {
                    this.generalErrors = [this.lang["unauthorized_error"]];
                }
                else if (error.response.status == 408) {
                    this.generalErrors = [this.lang["form_expired_error"]];
                }
                else if (error.response.status == 419) {
                    this.finishSubmit(error.response);
                    if (!this.csrfTokenRefreshed) {
                        this.refreshCsrfToken(callbackMethod);
                    }
                    else {
                        this.generalErrors = [this.lang["form_expired_error"]];
                    }
                    this.csrfTokenRefreshed = true;
                }
                else {
                    this.generalErrors = [this.lang["general_form_error"]];
                }
            }'
        );

        $this->vueInstance->addMethod(
            'refreshCsrfToken',
            'function(callbackMethod=null) {
                axios.get("/api/form-factory/csrf-token").then((response) => {
                    axios.defaults.headers.common["X-CSRF-TOKEN"] = response.data;
                    if (callbackMethod !== null) {
                        this[callbackMethod]();
                    }
                }).catch((error) => {
                    this.generalErrors = [this.lang["form_expired_error"]];
                });
            }'
        );

        $this->vueInstance->addMethod(
            'clearErrors',
            'function() {
                    this.errors = {};
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
            'reloadPage',
            'async function(delay) {
                    await new Promise(resolve => setTimeout(resolve, delay));
                    window.location.reload(true);
                }');

        $this->vueInstance->addMethod(
            'finishSubmit',
            'function(response, wasSuccessful=true) {
                    this.isSubmitting = false;
                    if (response && response.data.captcha_question) {
                        this.captchaQuestion = response.data.captcha_question;
                    }

                    // Scroll to (label of) first alert (error-message, success, etc.)
                    this.$nextTick().then(() => {
                        let firstAlert = this.$el.querySelector("[role=alert]");
                        if (firstAlert !== null) {
                            let scrollTarget = firstAlert;
                            let parentFieldWrapper = firstAlert.closest("[data-field-wrapper]");
                            if (parentFieldWrapper !== null) {
                                scrollTarget = parentFieldWrapper;
                            }
                            scrollTarget.scrollIntoView({ behavior: "smooth" });
                        }
                    });
                }');

        $this->vueInstance->addMethod(
            'displaySuccessMessage',
            config('formfactory.vue.methods.display_success_message')
        );

        $this->vueInstance->addMethod('fileAgentSelected', 'function (files, fieldName) {
            let headers = {
                "X-CSRF-TOKEN": "'.csrf_token().'"
            };
            let self = this;
            let isMultiple = (!!this.fields[fieldName].value) && (this.fields[fieldName].value.constructor === Array);
            files.forEach(function (fileData) {
                self.$refs["vueFileAgent_"+fieldName].upload("/api/form-factory/file-upload", headers, [fileData], function (fileData) {
                    self.isSubmitting = true;
                    let formData = new FormData();
                    formData.append("file", fileData.file);
                    formData.append("fieldName", fieldName);
                    formData.append("_formID", self.fields["_formID"].value);
                    return formData;
                }).then((response) => {
                    if (response.length > 0) {
                        delete fileData.xhr;
                        fileData.upload = response[0].data;
                        fileData.progress(100);
                        self.$forceUpdate();
                    }
                    self.$nextTick().then(() => {
                        self.isSubmitting = false;
                    });
                }).catch((error) => {
                    self.handleFieldErrors(error);
                    self.$nextTick().then(() => {
                        self.finishSubmit();
                    });
                });
            });
        }');

        // Workaround, since vueFileAgent sets this to empty array instead of string for non-multiple fields.
        $this->vueInstance->addMethod('fileAgentDeleted', 'function (fileData, fieldName) {
            if (!this.$refs["vueFileAgent_"+fieldName].multiple) {
                this.fields[fieldName].value = null;
            }
        }');

        $this->vueInstance->addMethod(
            'displayConfirmDialog',
            config('formfactory.vue.methods.display_confirm_dialog')
        );

        $this->vueInstance->addData('isSubmitting', false);
        $this->vueInstance->addData('csrfTokenRefreshed', false);
        $this->vueInstance->addData('hideForm', false);
        $this->vueInstance->addData('generalErrors', []);
        $this->vueInstance->addData('errors', (object)[]);
        $this->vueInstance->addData('successMessage', "");
        $this->vueInstance->addData('captchaQuestion', $this->form->getCaptchaQuestion());
    }

}

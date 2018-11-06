<?php

namespace Nicat\FormFactory\Components;

use Nicat\FormFactory\FormFactory;
use Nicat\FormFactory\Components\Additional\ErrorContainer;
use Nicat\FormFactory\Exceptions\FormRequestClassNotFoundException;
use Nicat\FormFactory\Exceptions\MandatoryOptionMissingException;
use Nicat\FormFactory\Utilities\Forms\FormInstance;
use Nicat\HtmlFactory\Attributes\MethodAttribute;
use Nicat\HtmlFactory\Elements\FormElement;

class Form extends FormElement
{

    /**
     * Automatically generate hidden CSRF-token-tag (enabled by default)?
     *
     * @var bool
     */
    protected $generateToken = true;

    /**
     * Display legend regarding mandatory fields?
     *
     * @var bool
     */
    protected $displayMandatoryFieldsLegend = true;

    /**
     * A method to spoof for laravel.
     *
     * @var string
     */
    protected $spoofedMethod;

    /**
     * ID of the modal-box, that should be opened on page-load, if errors occur.
     *
     * @var null|string
     */
    protected $modalId = null;

    /**
     * Set some default-setting.
     */
    protected function setUp()
    {
        $this->addRole('form');
        $this->acceptCharset('UTF-8');
        $this->enctype('multipart/form-data');

        if (config('formfactory.ajax_validation.enabled') && config('formfactory.ajax_validation.enable_on_form_submit_by_default')) {
            $this->ajaxValidation();
        }
    }

    /**
     * Apply some modifications.
     *
     * @throws MandatoryOptionMissingException
     * @throws \Nicat\FormFactory\Exceptions\FormInstanceNotFoundException
     */
    protected function beforeDecoration()
    {
        $this->appendCSRFToken();
        $this->appendHiddenFormId();
        $this->appendHiddenMethodSpoof();
        $this->setDefaultAction();
        $this->applyOpenModalOnLoad();

        $this->getFormInstance()->setUpAntiBotProtections();
    }

    /**
     * Remove the closing tag from output, since FormFactory closes the form-tag via method close().
     *
     * @param string $output
     */
    protected function manipulateOutput(string &$output)
    {
        $output = str_before($output, '</form>');
    }

    /**
     * Set the class-name of the request object.
     * (used for auto-adoption of rules, ajaxValidation, etc.)
     *
     * Also loads rules from the requestObject.
     *
     * Also saves in session, which request object is used for this particular form.
     * This is required for ajaxValidation.
     *
     * @param string $requestObject
     * @return $this
     * @throws FormRequestClassNotFoundException
     * @throws \Nicat\FormFactory\Exceptions\FormInstanceNotFoundException
     */
    public function requestObject(string $requestObject)
    {
        $this->getFormInstance()->setRequestObject($requestObject);
        return $this;
    }

    /**
     * Set value of HTML-attribute 'method'.
     * Overwritten to allow spoofed methods for laravel.
     *
     * @param string $method
     * @return $this
     */
    public function method($method)
    {
        $method = strtoupper($method);

        if (in_array($method, ['DELETE', 'PUT', 'PATCH'])) {
            $this->spoofedMethod = $method;
            $method = 'POST';
        }

        $this->attributes->establish(MethodAttribute::class)->setValue(strtoupper($method));
        return $this;
    }

    /**
     * Sets the ID of a modal, that should be opened on page-load, if any errors occur.
     * This is useful, if the form is located inside a bootstrap modal.
     *
     * @param $modalId
     * @return $this
     */
    public function modalId($modalId) {
        $this->modalId = $modalId;
        return $this;
    }

    /**
     * Enable / disable automatic generation of hidden CSRF-token-tag.
     * (Enabled by default)
     *
     * @param boolean $generateToken
     * @return $this
     */
    public function generateToken(bool $generateToken = true)
    {
        $this->generateToken = $generateToken;
        return $this;
    }

    /**
     * Append hidden input tag with CSRF-token (except for forms with a GET-method),
     * if $this->generateToken is not set to false.
     */
    protected function appendCSRFToken()
    {
        if ($this->generateToken && $this->attributes->method !== 'GET') {
            $csrfToken = csrf_token();
            if (is_null($csrfToken)) {
                $csrfToken = '';
            }
            $this->appendContent(
                FormFactory::singleton()->hidden('_token')->value($csrfToken)
            );
        }
    }

    /**
     * If the method is DELETE|PATCH|PUT, we spoof it laravel-style by adding a hidden '_method' field.
     */
    protected function appendHiddenMethodSpoof()
    {
        if (!is_null($this->spoofedMethod)) {
            $this->appendContent(
                FormFactory::singleton()->hidden('_method')->value($this->spoofedMethod)
            );
        }
    }

    /**
     * Append hidden input tag with the form-id.
     * This is used to find out, if a form was just submitted.
     */
    protected function appendHiddenFormId()
    {
        $this->appendContent(
            FormFactory::singleton()->hidden('_formID')->value($this->attributes->id)
        );
    }

    /**
     * Set default action to current URL, if none was set.
     */
    private function setDefaultAction()
    {
        if (!$this->attributes->isSet('action')) {
            $this->action(\URL::current());
        }
    }

    /**
     * Set default-values to be used for all fields.
     *
     * @param array $values
     * @return $this
     * @throws \Nicat\FormFactory\Exceptions\FormInstanceNotFoundException
     */
    public function values(array $values)
    {
        $this->getFormInstance()->values->setDefaultValues($values);
        return $this;
    }

    /**
     * Set errors to be used for all fields.
     * (omit for auto-adoption from session)
     *
     * @param array $errors
     * @return $this
     * @throws \Nicat\FormFactory\Exceptions\FormInstanceNotFoundException
     */
    public function errors(array $errors)
    {
        $this->getFormInstance()->errors->setErrors($errors);
        return $this;
    }

    /**
     * Sets the name of the Laravel-errorBag, where this form should look for errors.
     * (default = 'default')
     *
     * @param string $errorBag
     * @return $this
     * @throws \Nicat\FormFactory\Exceptions\FormInstanceNotFoundException
     */
    public function errorBag(string $errorBag)
    {
        $this->getFormInstance()->errors->setErrorBag($errorBag);
        return $this;
    }

    /**
     * Set rules to be used for all fields.
     * (omit for auto-adoption from request-object, if it is set via the requestObject()-method)
     *
     * @param array $rules
     * @return $this
     * @throws \Nicat\FormFactory\Exceptions\FormInstanceNotFoundException
     */
    public function rules(array $rules)
    {
        $this->getFormInstance()->rules->setRules($rules);
        return $this;
    }

    /**
     * If the ID of a modal was set via 'modalId()', and the form has errors,
     * we apply a corresponding data-attribute, so that our JS knows to open
     * that modal on page-load.
     */
    private function applyOpenModalOnLoad()
    {
        if (!is_null($this->modalId) && $this->getFormInstance()->errors->hasErrors()) {
            $this->data('openmodalonload', $this->modalId);
        }
    }

    /**
     * Returns the FormInstance this FormElement belongs to.
     *
     * @return FormInstance
     * @throws \Nicat\FormFactory\Exceptions\FormInstanceNotFoundException
     */
    private function getFormInstance()
    {
        return FormFactory::singleton()->getForm($this->attributes->id);
    }

    /**
     * Enables vue-functionality for this form.
     *
     * @return $this
     * @throws \Nicat\FormFactory\Exceptions\FormInstanceNotFoundException
     */
    public function enableVue()
    {
        $this->getFormInstance()->vueEnabled = true;
        return $this;
    }

    /**
     * Disables vue-functionality for this form.
     *
     * @return $this
     * @throws \Nicat\FormFactory\Exceptions\FormInstanceNotFoundException
     */
    public function disableVue()
    {
        $this->getFormInstance()->vueEnabled = false;
        return $this;
    }

}
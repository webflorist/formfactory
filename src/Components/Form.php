<?php

namespace Webflorist\FormFactory\Components;

use Webflorist\FormFactory\Utilities\AntiBotProtection\CaptchaProtection;
use Webflorist\FormFactory\Utilities\AntiBotProtection\HoneypotProtection;
use Webflorist\FormFactory\Utilities\AntiBotProtection\TimeLimitProtection;
use Webflorist\FormFactory\Components\Additional\ErrorContainer;
use Webflorist\FormFactory\Components\FormControls\HiddenInput;
use Webflorist\FormFactory\Exceptions\FormRequestClassNotFoundException;
use Webflorist\FormFactory\Exceptions\MandatoryOptionMissingException;
use Webflorist\FormFactory\Utilities\FieldErrors\FieldErrorManager;
use Webflorist\FormFactory\Utilities\FieldRules\FieldRuleManager;
use Webflorist\FormFactory\Utilities\FieldValues\FieldValueManager;
use Webflorist\HtmlFactory\Elements\FormElement;

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
     * Instance of the FieldRuleManager that handles management of field-rules.
     *
     * @var FieldRuleManager
     */
    public $rules = null;

    /**
     * Instance of the FieldErrorManager that handles management of field-errors.
     *
     * @var FieldErrorManager
     */
    public $errors = null;

    /**
     * Instance of the FieldValueManager that handles management of field-values.
     *
     * @var FieldValueManager
     */
    public $values = null;

    /**
     * Has this form been submitted with the last request?
     *
     * @var bool
     */
    public $wasSubmitted = false;

    /**
     * A method to spoof for laravel.
     *
     * @var string
     */
    protected $spoofedMethod;

    /**
     * Class-name of the form-request-object this form will validate against.
     *
     * @var null|string
     */
    public $requestObject = null;

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
        $this->values = new FieldValueManager($this);
        $this->errors = new FieldErrorManager($this);
        $this->rules = new FieldRuleManager($this);
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
     */
    protected function beforeDecoration()
    {
        $this->evaluateSubmittedState();
        $this->errors->fetchErrorsFromSession();
        $this->appendCSRFToken();
        $this->appendHiddenFormId();
        $this->appendHiddenMethodSpoof();
        $this->setDefaultAction();
        $this->appendHiddenGeneralErrorContainer();
        $this->applyOpenModalOnLoad();
        HoneypotProtection::setUp($this);
        TimeLimitProtection::setUp($this);
        CaptchaProtection::setUp($this);
    }


    /**
     * Remove the closing tag from output, since FormFactory closes the form-tag via method close().
     *
     * @param string $output
     */
    protected function manipulateOutput(string &$output)
    {
        $output = \Illuminate\Support\Str::before($output, '</form>');
    }

    /**
     * Evaluates, if this form been submitted via the last request.
     *
     * If there is a submitted field called "_formID", and it's value is the current form-id,
     * this form was indeed submitted during the last request.
     *
     */
    private function evaluateSubmittedState()
    {
        if (request()->old('_formID') === $this->attributes->id) {
            $this->wasSubmitted = true;
        }
    }

    /**
     * Set the class-name of the request object.
     * (used for auto-adoption of rules, ajaxValidation, etc.)
     *
     * Also loads rules from the requestObject.
     *
     * Also saves in session, which request object is used for this particular form.
     * This ist required for ajaxValidation.
     *
     * @param string $requestObject
     * @return $this
     * @throws FormRequestClassNotFoundException
     */
    public function requestObject(string $requestObject)
    {
        // Make sure, the submittedState of this form is correctly evaluated.
        $this->evaluateSubmittedState();

        // If class $requestObject does not exist, we try to prepend the namespace 'App\Http\Requests\'
        if (!class_exists($requestObject)) {
            if (class_exists('App\Http\Requests\\' . $requestObject)) {
                $requestObject = 'App\Http\Requests\\' . $requestObject;
            } else {
                throw new FormRequestClassNotFoundException('The form request class ' . $requestObject . ' could not be found!');
            }
        }

        // We set it as a property of this object.
        $this->requestObject = $requestObject;

        // We also link the request-object to this form in the session.
        // This is utilized by ajaxValidation.
        session()->put('formfactory.request_objects.' . $this->attributes->id, $requestObject);

        // Furthermore we fetch the rules from the requestObject (if no rules were manually set).
        $this->rules->fetchRulesFromRequestObject($requestObject);

        return $this;
    }

    /**
     * Set value of HTML-attribute 'method'.
     * Overwritten to allow spoofed methods for laravel.
     *
     * @param string $method
     * @return $this
     * @throws \Webflorist\HtmlFactory\Exceptions\AttributeNotAllowedException
     * @throws \Webflorist\HtmlFactory\Exceptions\AttributeNotFoundException
     */
    public function method(string $method)
    {
        $method = strtoupper($method);

        if (in_array($method, ['DELETE', 'PUT', 'PATCH'])) {
            $this->spoofedMethod = $method;
            $method = 'POST';
        }

        $this->attributes->establish('method')->setValue(strtoupper($method));
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
                (new HiddenInput())->name('_token')->value($csrfToken)
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
                (new HiddenInput())->name('_method')->value($this->spoofedMethod)
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
            (new HiddenInput())->name('_formID')->value($this->attributes->id)
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
     */
    public function values(array $values)
    {
        $this->values->setDefaultValues($values);
        return $this;
    }

    /**
     * Set errors to be used for all fields.
     * (omit for auto-adoption from session)
     *
     * @param array $errors
     * @return $this
     */
    public function errors(array $errors)
    {
        $this->errors->setErrors($errors);
        return $this;
    }

    /**
     * Sets the name of the Laravel-errorBag, where this form should look for errors.
     * (default = 'default')
     *
     * @param string $errorBag
     * @return $this
     */
    public function errorBag(string $errorBag)
    {
        $this->errors->setErrorBag($errorBag);
        return $this;
    }

    /**
     * Set rules to be used for all fields.
     * (omit for auto-adoption from request-object, if it is set via the requestObject()-method)
     *
     * @param array $rules
     * @return $this
     */
    public function rules(array $rules)
    {
        $this->rules->setRules($rules);
        return $this;
    }

    /**
     * Enables/disables ajax-validation onSubmit for this form.
     *
     * @param bool $enable
     * @return $this
     */
    public function ajaxValidation($enable = true)
    {
        if ($enable && config('formfactory.ajax_validation.enabled')) {
            $this->data('ajaxvalidation', 'onSubmit');
        } else {
            $this->attributes->remove('data-ajaxvalidation');
        }
        return $this;
    }

    /**
     * Append a hidden general-error-container for displaying general field-errors.
     */
    protected function appendHiddenGeneralErrorContainer()
    {
        $this->appendContent(
            (new ErrorContainer())->data('displays-general-errors',true)
        );
    }

    /**
     * If the ID of a modal was set via 'modalId()', and the form has errors,
     * we apply a corresponding data-attribute, so that our JS knows to open
     * that modal on page-load.
     */
    private function applyOpenModalOnLoad()
    {
        if (!is_null($this->modalId) && $this->errors->hasErrors()) {
            $this->data('openmodalonload', $this->modalId);
        }
    }

}
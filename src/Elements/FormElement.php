<?php

namespace Nicat\FormBuilder\Elements;

use Nicat\FormBuilder\AntiBotProtection\CaptchaProtection;
use Nicat\FormBuilder\AntiBotProtection\HoneypotProtection;
use Nicat\FormBuilder\AntiBotProtection\TimeLimitProtection;
use Nicat\FormBuilder\Exceptions\FormRequestClassNotFoundException;
use Nicat\FormBuilder\Exceptions\MandatoryOptionMissingException;
use Nicat\FormBuilder\FormBuilderTools;

class FormElement extends \Nicat\HtmlBuilder\Elements\FormElement
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
     * Array of all rules for all fields of this form, that were submitted via the 'rules'-method of this FormElement.
     *
     * @var array
     */
    public $rules = [];

    /**
     * Array of all errors for all fields of this form.
     *
     * @var array
     */
    public $errors = [];

    /**
     * Array of default-values for all fields of this form, that were submitted via the 'values'-method of this FormElement.
     *
     * @var array
     */
    private $defaultValues = [];

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
     * The Laravel errorBag, where this form should look for errors.
     *
     * @var string
     */
    protected $errorBag = 'default';

    /**
     * Set some default-setting.
     */
    protected function setUp()
    {
        $this->addRole('form');
        $this->acceptCharset('UTF-8');
        $this->enctype('multipart/form-data');

        if (config('formbuilder.ajax_validation.enabled') && config('formbuilder.ajax_validation.enable_on_form_submit_by_default')) {
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
        $this->appendCSRFToken();
        $this->appendHiddenFormId();
        $this->appendHiddenMethodSpoof();
        $this->setDefaultAction();
        HoneypotProtection::setUp($this);
        TimeLimitProtection::setUp($this);
        CaptchaProtection::setUp($this);
    }


    /**
     * Remove the closing tag from output, since FormBuilder closes the form-tag via method close().
     *
     * @param string $output
     */
    protected function manipulateOutput(string &$output)
    {
        $output = str_before($output, '</form>');
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
     * Sets the name of the Laravel-errorBag, where this form should look for errors.
     * (default = 'default')
     *
     * @param string $errorBag
     * @return $this
     */
    public function errorBag($errorBag)
    {
        $this->errorBag = $errorBag;
        return $this;
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
        session()->put('formbuilder.request_objects.' . $this->attributes->id, $requestObject);

        // Furthermore we load the rules from the requestObject into $this->rules (if no rules were manually set).
        if (count($this->rules) === 0) {
            $requestObjectInstance = FormBuilderTools::initFormRequestObject($requestObject);

            $rules = [];

            // If the request-object uses the getRules()-function of the nicat/extended-validation package,
            // We also fetch the rules returned by this method to get the wildcard-variants of array-fields.
            // This should not be required anymore from Laravel 5.2 on.
            if (method_exists($requestObjectInstance, 'getRules')) {
                $rules = array_merge($rules, $requestObjectInstance->getRules());
            }

            // Merge rules-array from request-object.
            $rules = array_merge($rules, $requestObjectInstance->rules());

            $this->rules($rules);
        }

        // If this form was just submitted, we also fetch any errors from the session
        // and put them into $this->errors (if no errors were manually set).
        if ($this->wasSubmitted && (count($this->errors) === 0) && session()->has('errors')) {
            $errorBag = session()->get('errors');
            if (is_a($errorBag, 'Illuminate\Support\ViewErrorBag')) {
                /** @var \Illuminate\Support\ViewErrorBag $errorBag */
                $errors = $errorBag->getBag($this->errorBag)->toArray();
                if (count($errors) > 0) {
                    $this->errors = $errors;
                }
            }
        }

        return $this;
    }

    /**
     * Set value of HTML-attribute 'method'.
     * Overwritten to allow spoofed methods for laravel.
     *
     * @param string $method
     * @return $this
     * @throws \Nicat\HtmlBuilder\Exceptions\AttributeNotAllowedException
     * @throws \Nicat\HtmlBuilder\Exceptions\AttributeNotFoundException
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
            $this->appendChild(
                (new HiddenInputElement())->name('_token')->value($csrfToken)
            );
        }
    }

    /**
     * If the method is DELETE|PATCH|PUT, we spoof it laravel-style by adding a hidden '_method' field.
     */
    protected function appendHiddenMethodSpoof()
    {
        if (!is_null($this->spoofedMethod)) {
            $this->appendChild(
                (new HiddenInputElement())->name('_method')->value($this->spoofedMethod)
            );
        }
    }

    /**
     * Append hidden input tag with the form-id.
     * This is used to find out, if a form was just submitted.
     */
    protected function appendHiddenFormId()
    {
        $this->appendChild(
            (new HiddenInputElement())->name('_formID')->value($this->attributes->id)
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
        $this->defaultValues = $values;
        return $this;
    }

    /**
     * Gets the default-value of a field stored in this FormElement via the 'values'-method.
     *
     * @param string $fieldName
     * @return string|null
     */
    public function getDefaultValueForField(string $fieldName = '')
    {
        $fieldName = FormBuilderTools::convertArrayFieldHtmlName2DotNotation($fieldName);
        if (array_has($this->defaultValues, $fieldName)) {
            return (array_get($this->defaultValues, $fieldName));
        }
        return null;
    }

    /**
     * Checks, if a default-value of a field was stored in this FormElement via the 'values'-method.
     *
     * @param string $fieldName
     * @return bool
     */
    public function fieldHasDefaultValue(string $fieldName = '')
    {
        $fieldName = FormBuilderTools::convertArrayFieldHtmlName2DotNotation($fieldName);
        return array_has($this->defaultValues, $fieldName);
    }


    /**
     * Gets the submitted value of a field for the current form
     *
     * @param string $fieldName
     * @return mixed
     */
    public function getSubmittedValueForField(string $fieldName)
    {
        if ($this->wasSubmitted) {
            $fieldName = FormBuilderTools::convertArrayFieldHtmlName2DotNotation($fieldName);
            return request()->old($fieldName);
        }
        return null;
    }

    /**
     * Checks, if a field was submitted for the current form
     *
     * @param string $fieldName
     * @return bool
     */
    public function fieldHasSubmittedValue(string $fieldName) : bool
    {
        $fieldName = FormBuilderTools::convertArrayFieldHtmlName2DotNotation($fieldName);
        return $this->wasSubmitted && !is_null(request()->old($fieldName));
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
        $this->errors = $errors;
        return $this;
    }

    /**
     * Gets the error(s) of a field currently stored in the FormBuilder-object.
     *
     * @param string $name
     * @return array
     */
    public function getErrorsForField(string $name): array
    {
        $name = FormBuilderTools::convertArrayFieldHtmlName2DotNotation($name);

        if (isset($this->errors[$name]) > 0) {
            return $this->errors[$name];
        }

        // If no errors were found, we simply return an empty array.
        return [];
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
        foreach ($rules as $fieldName => $fieldRules) {
            $this->rules[$fieldName] = FormBuilderTools::parseRules($fieldRules);
        }
        return $this;
    }

    /**
     * Gets the rules of a field of this form.
     *
     * @param string $name
     * @return array
     */
    public function getRulesForField(string $name): array
    {
        $isArray = FormBuilderTools::isArrayField($name);

        // If the name is an array-key (e.g. "domainName[domainLabel]"), we have to convert it into dot-notation to access it's rules
        if ($isArray) {
            $name = FormBuilderTools::convertArrayFieldHtmlName2DotNotation($name);
        }

        // If rules for this field are present in $this->rules, we return them.
        if (isset($this->rules[$name])) {
            return $this->rules[$name];
        }

        // If the field is an array, we also look for a rule defined via wildcard.
        // E.g.: If there are rules defined for "domainList.*.domainName",
        // we must return them, if the current field is called "domainList[0][domainName]"
        if ($isArray) {
            foreach ($this->rules as $ruleField => $ruleSet) {
                if (strpos($ruleField, '.*') !== false) {
                    $ruleFieldRegex = str_replace('.*', '.([0-9]*)', $ruleField);
                    if (preg_match("/^(" . $ruleFieldRegex . ")$/", $name)) {
                        return $this->rules[$ruleField];
                    }
                }
            }
        }

        // If no rules were found, we simply return an empty array.
        return [];
    }

    /**
     * Enables/disables ajax-validation onSubmit for this form.
     *
     * @param bool $enable
     * @return $this
     */
    public function ajaxValidation($enable = true)
    {
        if ($enable && config('formbuilder.ajax_validation.enabled')) {
            $this->data('ajaxvalidation', 'onSubmit');
        } else {
            $this->attributes->remove('data-ajaxvalidation');
        }
        return $this;
    }

}
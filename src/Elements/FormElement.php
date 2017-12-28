<?php

namespace Nicat\FormBuilder\Elements;

use Illuminate\Cache\RateLimiter;
use Nicat\FormBuilder\Components\FieldWrapper;
use Nicat\FormBuilder\Exceptions\FormRequestClassNotFoundException;
use Nicat\FormBuilder\Exceptions\MandatoryOptionMissingException;
use Nicat\FormBuilder\FormBuilderTools;
use Request;

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
     * Set some default-setting.
     */
    protected function setUp()
    {
        $this->addRole('form');
        $this->acceptCharset('UTF-8');
        $this->enctype('multipart/form-data');
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
        $this->handleHoneypotProtection();
        $this->handleCaptchaProtection();
        $this->setDefaultAction();
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
        if (Request::old('_formID') === $this->attributes->getValue('id')) {
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

        // ... as well as in the session.
        \Session::put('formBuilder.requestObjects.' . $this->attributes->getValue('id'), $requestObject);

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
        if ($this->generateToken && $this->attributes->getValue('method') !== 'GET') {
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
            (new HiddenInputElement())->name('_formID')->value($this->attributes->getValue('id'))
        );
    }

    /**
     * Append the honeypot-field, if honeypot-protection is enabled in the config.
     */
    protected function handleHoneypotProtection()
    {
        if (config('formbuilder.honeypot.enabled')) {

            // We retrieve the honeypot-rules.
            $honeypotRules = $this->getRulesForField('_honeypot');

            // If there are any, ...
            if (count($honeypotRules) > 0) {

                // ...we add the honeypot-field wrapped in a hidden wrapper.
                $honeypotField = (new TextInputElement())
                    ->name(FormBuilderTools::getHoneypotFieldName())
                    ->value("")
                    ->label(trans('Nicat-FormBuilder::formbuilder.honeypot_field_label'))
                    ->addErrorField('_honeypot');
                $honeypotField->wrap(
                    (new FieldWrapper($honeypotField))->hidden()
                );
                $this->appendChild(
                    $honeypotField
                );
            }
        }
    }

    /**
     * Handle setting the session-info and generation of the captcha-field, if captcha-protection is enabled in the config.
     *
     * @throws MandatoryOptionMissingException
     */
    protected function handleCaptchaProtection()
    {
        if (config('formbuilder.captcha.enabled')) {

            // We retrieve the captcha-rules.
            $captchaRules = $this->getRulesForField('_captcha');

            // If there are any, ...
            if (count($captchaRules)>0) {

                // Captcha-protection only works, if a request-object was stated via the requestObject() method,
                // so we throw an exception, if this was not the case.
                if (is_null($this->requestObject)) {
                    throw new MandatoryOptionMissingException(
                        'The form with ID "'.$this->attributes->getValue('id').'" should display a captcha, '.
                        'but no request-object was stated via the Form::open()->requestObject() method. '.
                        'Captcha only works if this is the case.'
                    );
                }

                // Set where the captcha-answer will be stored in the session.
                $sessionKeyForCaptchaData = 'htmlBuilder.formBuilder.captcha.' . $this->requestObject;

                // We unset any old captcha-answer (from the previous request) currently set in the session for this request-object.
                $oldFlashKeys = Request::session()->get('flash.old');
                if (is_array($oldFlashKeys) && in_array($sessionKeyForCaptchaData,$oldFlashKeys)) {
                    Request::session()->forget($sessionKeyForCaptchaData);
                }

                // Get the rule-parameters for the 'captcha'-rule.
                $ruleParameters = $captchaRules['captcha'];

                // If a specific limit is set for this request via the first rule-parameter, we use this value.
                if (isset($ruleParameters[0]) && is_numeric($ruleParameters[0])) {
                    $requestLimit = $ruleParameters[0];
                }
                // Otherwise we use the default-value set in the config.
                else {
                    $requestLimit = config('formbuilder.captcha.default_limit');
                }

                // If a specific decay-time is set for this request via the first rule-parameter, we use this value.
                if (isset($ruleParameters[1]) && is_numeric($ruleParameters[1])) {
                    $decayTime = $ruleParameters[1];
                }
                // Otherwise we use the default-value set in the config.
                else {
                    $decayTime = config('formbuilder.captcha.decay_time');
                }

                // Now let's see, if the limit for this particular request has been reached.
                // We use the laravel-built in RateLimiter for that.
                // The Key of the RateLimiter is a hash of the RequestObject and the client-IP.
                $rateLimiterKey = sha1($this->requestObject. Request::ip());

                // A requestLimit of 0 means, a captcha is always required.
                if (($requestLimit === "0") || app(RateLimiter::class)->tooManyAttempts($rateLimiterKey, $requestLimit, $decayTime)) {

                    // If it has been reached, we must append a captcha-field.

                    // If the same request-object is used in multiple forms of a page,
                    // there might already be captchaData in the session.
                    // If this is the case, we use that.
                    if (Request::session()->has($sessionKeyForCaptchaData)) {
                        $captchaData = Request::session()->get($sessionKeyForCaptchaData);
                    }
                    // Otherwise...
                    else {
                        // ...we generate a captcha-question and an answer.
                        $captchaData = FormBuilderTools::generateCaptchaData();

                        // Furthermore we also set the required captcha-answer in the session.
                        // This is used when the CaptchaValidator actually checks the captcha.
                        Request::session()->flash($sessionKeyForCaptchaData, $captchaData);
                    }

                    // Then we add the captcha-field to the output.
                    $this->appendChild(
                        (new TextInputElement())
                            ->name('_captcha')
                            ->required(true)
                            ->value('')
                            ->label($captchaData['question'])
                            ->placeholder(trans('Nicat-FormBuilder::formbuilder.captcha_placeholder'))
                            ->helpText(trans('Nicat-FormBuilder::formbuilder.captcha_help_text'))
                    );

                }
            }
        }
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
        if (array_has($this->defaultValues, $fieldName)) {
            return true;
        }
        return false;
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

        // TODO: look via request-object.

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

}
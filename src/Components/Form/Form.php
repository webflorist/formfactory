<?php

namespace Webflorist\FormFactory\Components\Form;

use Webflorist\FormFactory\Components\FormControls\Contracts\FormControlInterface;
use Webflorist\FormFactory\Components\FormControls\HiddenInput;
use Webflorist\FormFactory\Components\FormControls\Select;
use Webflorist\FormFactory\Components\FormControls\TextInput;
use Webflorist\FormFactory\Components\Helpers\ErrorContainer;
use Webflorist\FormFactory\Exceptions\OpenElementNotFoundException;
use Webflorist\FormFactory\Exceptions\FormRequestClassNotFoundException;
use Webflorist\FormFactory\Exceptions\MandatoryOptionMissingException;
use Webflorist\FormFactory\Components\Form\AntiBotProtection\CaptchaProtection;
use Webflorist\FormFactory\Components\Form\AntiBotProtection\HoneypotProtection;
use Webflorist\FormFactory\Components\Form\AntiBotProtection\TimeLimitProtection;
use Webflorist\FormFactory\Components\Form\FieldErrors\FieldErrorManager;
use Webflorist\FormFactory\Components\Form\FieldRules\FieldRuleManager;
use Webflorist\FormFactory\Components\Form\FieldValues\FieldValueManager;
use Webflorist\HtmlFactory\Attributes\MethodAttribute;
use Webflorist\HtmlFactory\Elements\Abstracts\Element;
use Webflorist\HtmlFactory\Elements\FormElement;
use Webflorist\HtmlFactory\Elements\TemplateElement;

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
     * Array of FormControls that belong to this Form.
     *
     * @var FormControlInterface[]|Element[]
     */
    private $formControls;

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
     * Class-name of the form-request-object this form will validate against.
     *
     * @var null|string
     */
    protected $requestObject = null;

    /**
     * Is this form currently open?
     * (Form::open() was called but Form::close() was not yet called.)
     *
     * @var bool
     */
    private $isOpen = true;

    /**
     * Has a required-field-indicator been rendered,
     * and thus should the required-fields-legend be displayed?
     *
     * @var bool
     */
    private $requiredFieldIndicatorUsed = false;

    /**
     * Has this form been submitted with the last request?
     *
     * @var bool
     */
    public $wasSubmitted = false;

    /**
     * Last Select-FormControl added to this form.
     *
     * @var Select
     */
    protected $lastSelect = null;

    /**
     * The CaptchaProtection object associated with this form,
     * if captcha-protection is used.
     *
     * @var CaptchaProtection|null
     */
    protected $captchaProtection = null;

    /**
     * Form constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        parent::__construct();

        $this->id($id);
        $this->evaluateSubmittedState();
        $this->values = new FieldValueManager($this);
        $this->errors = new FieldErrorManager($this);
        $this->rules = new FieldRuleManager($this);
        $this->addRole('form');
        $this->acceptCharset('UTF-8');
        $this->enctype('multipart/form-data');
        $this->method('post');
    }

    /**
     * Set the class-name of the request object.
     * (used for auto-adoption of rules, values and errors)
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
     * Gets ID of the form.
     *
     * @return string
     */
    public function getId()
    {
        return $this->attributes->id;
    }

    /**
     * Is this form currently open?
     * (Form::open() was called but Form::close() was not yet called.)
     *
     * @return bool
     */
    public function isOpen(): bool
    {
        return $this->isOpen;
    }

    /**
     * Returns the FormElement of this Form.
     *
     * @return Form
     */
    public function getFormElement()
    {
        return $this;
    }

    /**
     * Has the RequiredFieldIndicator been used for this form?
     *
     * @return bool
     */
    public function wasRequiredFieldIndicatorUsed(): bool
    {
        return $this->requiredFieldIndicatorUsed;
    }

    /**
     * Set, if the RequiredFieldIndicator has been used for this form.
     *
     * @param bool $requiredFieldIndicatorUsed
     */
    public function setRequiredFieldIndicatorUsed(bool $requiredFieldIndicatorUsed = true)
    {
        $this->requiredFieldIndicatorUsed = $requiredFieldIndicatorUsed;
    }

    /**
     * Mark this form as closed.
     * (= the Form::close() call was made.)
     */
    public function closeForm()
    {
        $this->isOpen = false;
    }

    /**
     * Apply some modifications.
     *
     * @throws MandatoryOptionMissingException
     */
    protected function beforeDecoration()
    {

        $this->appendCSRFToken();
        $this->appendHiddenFormId();
        $this->appendHiddenMethodSpoof();
        $this->setDefaultAction();

        HoneypotProtection::setUp($this);
        $this->setUpTimeLimit();
        $this->setUpCaptcha();
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
     * Append hidden input tag with CSRF-token (except for forms with a GET-method),
     * if $this->generateToken is not set to false.
     */
    protected function appendCSRFToken()
    {
        if (
            $this->generateToken &&
            $this->attributes->method !== 'GET')
        {
            $csrfToken = csrf_token();
            if (is_null($csrfToken)) {
                $csrfToken = '';
            }
            $this->appendContent(
                (new HiddenInput('_token'))->value($csrfToken)
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
                (new HiddenInput('_method'))->value($this->spoofedMethod)
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
            (new HiddenInput('_formID'))->value($this->attributes->id)
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
     * Evaluates, if this form been submitted via the last request.
     *
     * If there is a submitted field called "_formID", and it's value is the current form-id,
     * this form was indeed submitted during the last request.
     *
     */
    private function evaluateSubmittedState()
    {
        if (request()->old('_formID') === $this->getId()) {
            $this->wasSubmitted = true;
        }
    }

    /**
     * Returns the last added Select-element.
     *
     * @return Select
     * @throws OpenElementNotFoundException
     */
    public function getLastSelect(): Select
    {
        if (is_null($this->lastSelect)) {
            throw new OpenElementNotFoundException('FormFactory could not find a currently open Select-element while generating an Option-element. This is probably due to generating a option-field with FormFactory without opening a select using Form::select() before that.');
        }

        return $this->lastSelect;
    }

    /**
     * Registers a FormControl with this Form
     *
     * @param FormControlInterface $formControlElement
     */
    public function registerFormControl(FormControlInterface $formControlElement)
    {
        $this->formControls[] = $formControlElement;
        if ($formControlElement->is(Select::class)) {
            $this->lastSelect = $formControlElement;
        }
    }

    /**
     * Returns $this->formControls.
     *
     * @return FormControlInterface[]|Element[]
     */
    public function getFormControls()
    {
        return $this->formControls;
    }

    /**
     * Perform setup tasks for Captcha-protection.
     *
     * @throws MandatoryOptionMissingException
     */
    private function setUpCaptcha()
    {
        if (config('formfactory.captcha.enabled')) {
            $captchaRules = $this->rules->getRulesForField('_captcha');

            if (isset($captchaRules['captcha'])) {

                // Captcha-protection only works, if a request-object was stated via the requestObject() method,
                // so we throw an exception, if this was not the case.
                if (is_null($this->requestObject)) {
                    throw new MandatoryOptionMissingException(
                        'The form with ID "' . $this->getId() . '" should display a captcha, ' .
                        'but no request-object was stated via the Form::open()->requestObject() method. ' .
                        'Captcha only works if this is the case.'
                    );
                }
                $this->captchaProtection = (new CaptchaProtection(
                    $this->requestObject,
                    $captchaRules['captcha']
                ));
                $this->captchaProtection->setUp();
                $this->appendCaptchaField();
            }
        }
    }

    /**
     * Retrieves the current Captcha-question for this form,
     * if captcha is enabled and required.
     *
     * @return null|string
     */
    public function getCaptchaQuestion()
    {
        if (!is_null($this->captchaProtection)) {
            return $this->captchaProtection->getQuestion();
        }

        return null;
    }

    /**
     * Appends the captcha-field to this form.
     */
    protected function appendCaptchaField()
    {
        if ($this->captchaProtection->isRequestLimitReached()) {
            $this->appendContent($this->getCaptchaField());
        }
    }

    /**
     * Returns the TextInput for the captcha.
     *
     * @return TextInput
     */
    protected function getCaptchaField(): TextInput
    {
        return (new TextInput('_captcha'))
            ->required(true)
            ->value('')
            ->label($this->getCaptchaQuestion())
            ->placeholder(trans('webflorist-formfactory::formfactory.captcha_placeholder'))
            ->helpText(trans('webflorist-formfactory::formfactory.captcha_help_text'));
    }

    /**
     * Perform setup tasks for TimeLimit-protection.
     *
     * @throws MandatoryOptionMissingException
     */
    private function setUpTimeLimit()
    {

        if (config('formfactory.time_limit.enabled')) {
            $timeLimitRules = $this->rules->getRulesForField('_timeLimit');

            if (isset($timeLimitRules['timeLimit'])) {

                // TimeLimit-protection only works, if a request-object was stated via the requestObject() method,
                // so we throw an exception, if this was not the case.
                if (is_null($this->requestObject)) {
                    throw new MandatoryOptionMissingException(
                        'The form with ID "' . $this->getId() . '" should be protected by a time-limit, ' .
                        'but no request-object was stated via the Form::open()->requestObject() method. ' .
                        'TimeLimit-protection only works if this is the case.'
                    );
                }
                TimeLimitProtection::setUp($this->requestObject);

                // We also add an errorContainer to display any errors for '_timeLimit' to the form.
                $this->appendContent(new ErrorContainer('_timeLimit'));
            }
        }
    }

}

<?php

namespace Nicat\FormFactory\Components\Form;

use Nicat\FormFactory\Components\FormControls\Contracts\FormControlInterface;
use Nicat\FormFactory\Components\FormControls\HiddenInput;
use Nicat\FormFactory\Components\FormControls\Select;
use Nicat\FormFactory\Components\FormControls\TextInput;
use Nicat\FormFactory\Components\Helpers\ErrorContainer;
use Nicat\FormFactory\Exceptions\OpenElementNotFoundException;
use Nicat\FormFactory\Exceptions\FormRequestClassNotFoundException;
use Nicat\FormFactory\Exceptions\MandatoryOptionMissingException;
use Nicat\FormFactory\Components\Form\AntiBotProtection\CaptchaProtection;
use Nicat\FormFactory\Components\Form\AntiBotProtection\HoneypotProtection;
use Nicat\FormFactory\Components\Form\AntiBotProtection\TimeLimitProtection;
use Nicat\FormFactory\Components\Form\FieldErrors\FieldErrorManager;
use Nicat\FormFactory\Components\Form\FieldRules\FieldRuleManager;
use Nicat\FormFactory\Components\Form\FieldValues\FieldValueManager;
use Nicat\HtmlFactory\Attributes\MethodAttribute;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\DivElement;
use Nicat\HtmlFactory\Elements\FormElement;
use Nicat\HtmlFactory\Elements\TemplateElement;

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
    public $requestObject = null;

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
     * Is vue-functionality enabled for this form?
     *
     * @var bool
     */
    protected $vueEnabled = null;

    /**
     * The CaptchaProtection object associated with this form,
     * if captcha-protection is used.
     *
     * @var CaptchaProtection|null
     */
    protected $captchaProtection = null;

    /**
     * If vue is anabled, should the vue-app be generated immediately after the Form::close() call?
     * It's default-behaviour can be set via the config 'formfactory.vue.default'
     *
     * Set $this->enableVue(false) to
     *
     * @var bool
     */
    public $appendVueApp = null;

    /**
     * Form constructor.
     * @param $id
     */
    public function __construct($id)
    {
        parent::__construct();

        $this->id($id);
        $this->evaluateSubmittedState();
        $this->values = new FieldValueManager($this);
        $this->errors = new FieldErrorManager($this);
        $this->rules = new FieldRuleManager($this);
        $this->vueEnabled = config('formfactory.vue.default');
        $this->appendVueApp = config('formfactory.vue.auto_vue_app');
        $this->addRole('form');
        $this->acceptCharset('UTF-8');
        $this->enctype('multipart/form-data');
        $this->method('post');
    }


    /**
     * Apply some modifications.
     *
     * @throws MandatoryOptionMissingException
     */
    protected function beforeDecoration()
    {

        if ($this->isVueEnabled()) {
            $this->applyVueModifications();
        }

        $this->appendCSRFToken();
        $this->appendHiddenFormId();
        $this->appendHiddenMethodSpoof();
        $this->setDefaultAction();
        $this->applyOpenModalOnLoad();

        $this->setUpAntiBotProtections();
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

        // We also link the request-object to this form in the session.
        // This is utilized by ajaxValidation.
        session()->put('formfactory.request_objects.' . $this->getId(), $requestObject);

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
     * Sets the ID of a modal, that should be opened on page-load, if any errors occur.
     * This is useful, if the form is located inside a bootstrap modal.
     *
     * @param $modalId
     * @return $this
     */
    public function modalId($modalId)
    {
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

    /**
     * Enables vue-functionality for this form.
     *
     * @param null|bool $appendVueApp
     * @return $this
     */
    public function enableVue($appendVueApp=null)
    {
        if (is_bool($appendVueApp)) {
            $this->appendVueApp = $appendVueApp;
        }
        $this->vueEnabled = true;
        return $this;
    }

    /**
     * Disables vue-functionality for this form.
     *
     * @return $this
     */
    public function disableVue()
    {
        $this->vueEnabled = false;
        return $this;
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
     * Is this form currently set to use vue?
     *
     * @return bool
     */
    public function isVueEnabled(): bool
    {
        return config('formfactory.vue.enabled') && $this->vueEnabled;
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
     * Sets up various AntiBotProtections.
     *
     * @throws \Nicat\FormFactory\Exceptions\MandatoryOptionMissingException
     */
    public function setUpAntiBotProtections()
    {
        HoneypotProtection::setUp($this);
        TimeLimitProtection::setUp($this);
        $this->setUpCaptcha();
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
     * Apply various modifications to this Form, if vue is enabled.
     */
    private function applyVueModifications()
    {
        $this->vOn('submit', 'submitForm', ['prevent']);
        $this->appendContent(
            (new ErrorContainer())
                ->appendContent(
                    (new DivElement())->vFor("error in generalErrors")->content('{{ error }}')
                )
                ->vIf("generalErrors.length")
                ->wrap(new TemplateElement())
        );
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

    public function getCaptchaQuestion()
    {
        if (!is_null($this->captchaProtection)) {
            return $this->captchaProtection->getQuestion();
        }

        return null;
    }

    private function appendCaptchaField()
    {
        if ($this->captchaProtection->isRequestLimitReached() || $this->isVueEnabled()) {

            $captchaField = (new TextInput('_captcha'))
                ->required(true)
                ->value('')
                ->label($this->getCaptchaQuestion())
                ->placeholder(trans('Nicat-FormFactory::formfactory.captcha_placeholder'))
                ->helpText(trans('Nicat-FormFactory::formfactory.captcha_help_text'));

            if ($this->isVueEnabled()) {
                $captchaField->label->setText('{{ captchaQuestion }}');
                $captchaField->wrapper->vIf('captchaQuestion');
                $captchaField->wrapper->wrap(new TemplateElement());
            }

            $this->appendContent($captchaField);

        }

    }

}
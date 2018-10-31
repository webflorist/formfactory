<?php

namespace Nicat\FormFactory\Utilities\Forms;

use Nicat\FormFactory\Components\Form;
use Nicat\FormFactory\Components\FormControls\CheckboxInput;
use Nicat\FormFactory\Components\FormControls\Optgroup;
use Nicat\FormFactory\Components\FormControls\Option;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\FormFactory\Components\FormControls\Select;
use Nicat\FormFactory\Exceptions\FormRequestClassNotFoundException;
use Nicat\FormFactory\Exceptions\OpenElementNotFoundException;
use Nicat\FormFactory\Utilities\AntiBotProtection\CaptchaProtection;
use Nicat\FormFactory\Utilities\AntiBotProtection\HoneypotProtection;
use Nicat\FormFactory\Utilities\AntiBotProtection\TimeLimitProtection;
use Nicat\FormFactory\Utilities\FieldErrors\FieldErrorManager;
use Nicat\FormFactory\Utilities\FieldRules\FieldRuleManager;
use Nicat\FormFactory\Utilities\FieldValues\FieldValueManager;
use Nicat\HtmlFactory\Elements\Abstracts\Element;

/**
 * Manages persistent data for generated forms.
 *
 * Class FormsManager
 * @package Nicat\FormFactory
 */
class FormInstance
{

    /**
     * The HTML-FormElement of this form.
     *
     * @var Form
     */
    private $formElement;

    /**
     * Array of FormControls that belong to this FormInstance.
     *
     * @var Element[]
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
     * Last Select-FormControl added to this form..
     *
     * @var Select
     */
    protected $lastSelect = null;

    /**
     * FormInstance constructor.
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->formElement = $form;
        $this->evaluateSubmittedState();
        $this->values = new FieldValueManager($this);
        $this->errors = new FieldErrorManager($this);
        $this->rules = new FieldRuleManager($this);
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
        return $this->formElement->attributes->id;
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
     * Returns the FormElement of this FormInstance.
     *
     * @return Form
     */
    public function getFormElement()
    {
        return $this->formElement;
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
    public function setRequestObject(string $requestObject)
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
     * Sets up various AntiBotProtections.
     *
     * @throws \Nicat\FormFactory\Exceptions\MandatoryOptionMissingException
     */
    public function setUpAntiBotProtections()
    {
        HoneypotProtection::setUp($this);
        TimeLimitProtection::setUp($this);
        CaptchaProtection::setUp($this);
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
     * Registers a FormControl with this FormInstance
     *
     * @param Element $formControlElement
     * @throws OpenElementNotFoundException
     */
    public function registerFormControl(Element $formControlElement)
    {
        $this->formControls[] = $formControlElement;
        if ($formControlElement->is(Select::class)) {
            $this->lastSelect = $formControlElement;
        }

        $formControlElement->setFormInstance($this);
    }

    /**
     * Returns $this->formControls.
     *
     * @return Element[]
     */
    public function getFormControls()
    {
        return $this->formControls;
    }

}
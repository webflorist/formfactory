<?php

namespace Webflorist\FormFactory\Components\FormControls\Traits;

use Webflorist\FormFactory\Components\Form\Form;
use Webflorist\FormFactory\Components\Form\VueForm;
use Webflorist\FormFactory\Components\FormControls\HiddenInput;
use Webflorist\FormFactory\Components\Helpers\FieldWrapper;
use Webflorist\FormFactory\Components\FormControls\Contracts\FieldInterface;
use Webflorist\FormFactory\Components\FormControls\FileInput;
use Webflorist\FormFactory\Components\FormControls\Optgroup;
use Webflorist\FormFactory\Components\FormControls\Option;
use Webflorist\FormFactory\Components\FormControls\RadioInput;
use Webflorist\FormFactory\Exceptions\OpenElementNotFoundException;
use Webflorist\FormFactory\FormFactory;
use Webflorist\FormFactory\Components\Helpers\ErrorContainer;
use Webflorist\FormFactory\Components\Helpers\HelpTextContainer;
use Webflorist\FormFactory\Components\Helpers\FieldLabel;
use Webflorist\FormFactory\Components\Form\FieldRules\FieldRuleProcessor;
use Webflorist\FormFactory\Components\Form\FieldValues\FieldValueProcessor;
use Webflorist\HtmlFactory\Components\Traits\HasLayout;
use Webflorist\HtmlFactory\Elements\ButtonElement;

/**
 * This traits provides basic functionality for FormControls.
 *
 * @package Webflorist\FormFactory
 */
trait FormControlTrait
{
    use HasLayout;

    /**
     * The Form this Field belongs to.
     *
     * @var null|Form
     */
    private $form = null;

    /**
     * Is vue-functionality enabled for this form-control?
     *
     * @var bool
     */
    private $vueEnabled = true;

    /**
     * Is this FormControl part of a (e.g. input-)group?
     *
     * @var bool
     */
    public $inGroup = false;

    /**
     * Performs various Setup-tasks for this FormControl.
     *
     * TODO: This is all very smelly. Find better solution.
     */
    protected function setupFormControl()
    {
        // Register the FormControl with the currently open Form.
        try {
            $this->form = FormFactory::singleton()->getOpenForm();
            $this->form->registerFormControl($this);
        } catch (OpenElementNotFoundException $e) {
        }

        // Set a default-ID for various FormControls.
        $this->setDefaultId();

        if ($this->isAField()) {

            if ($this->canHaveWrapper()) {
                $this->wrapper = new FieldWrapper($this);
            }
            if ($this->canHaveErrors()) {
                $this->errors = new ErrorContainer($this);
            }
            if ($this->canHaveLabel()) {
                $this->label = new FieldLabel($this);
            }
        }

        // Apply help-text.
        if ($this->canHaveHelpText()) {
            $this->helpText = new HelpTextContainer($this);
        }
    }

    private function applyView()
    {
        try {
            $view = 'webflorist-formfactory::form-controls.' . kebab_case((new \ReflectionClass($this))->getShortName());
            $this->view($view);
        } catch (\ReflectionException $e) {
        }
    }

    /**
     * Performs various tasks before decoration.
     *
     * TODO: This is all very smelly. Find better solution.
     */
    protected function processFormControl()
    {

        // Apply view.
        $this->applyView();

        if ($this->isAField()) {

            if ($this->canHaveRules()) {
                FieldRuleProcessor::process($this);
            }

            FieldValueProcessor::process($this);

            if ($this->canHaveLabel()) {
                $this->label->generate();
            }

            if ($this->isVueEnabled()) {
                $this->applyVueDirectives();
            }

            if ($this->canHaveErrors()) {
                $this->errors->generate();
            }
        }

        if ($this->canHaveHelpText()) {
            $this->helpText->generate();
        }

    }

    protected function applyVueDirectives()
    {
        $fieldName = $this->attributes->name;
        $fieldBase = "fields['$fieldName']";
        if (!$this->attributes->isSet('v-model') && !$this->is(FileInput::class)) {
            $this->vModel($fieldBase . '.value');
        }
        if (!$this->is(HiddenInput::class)) {
            if ($this->is(FileInput::class) && (!$this->attributes->isSet('v-bind:value'))) {
                $this->vOnChange("handleFileInputChange('$fieldName', \$event)");
            }
            if (!$this->attributes->isSet('v-bind:required')) {
                $this->vBind('required', $fieldBase . '.isRequired');
            }
            if (!$this->attributes->isSet('v-bind:disabled')) {
                $this->vBind('disabled', $fieldBase . '.isDisabled');
            }
            if (!$this->attributes->isSet('v-bind:aria-invalid')) {
                $this->vBind('aria-invalid', "fieldHasError('$fieldName')");
            }
            if (!$this->attributes->isSet('v-bind:aria-describedby')) {
                $this->vBind('aria-describedby', $this->getAriaDescribedByExpression());
            }
        }
    }

    protected function getAriaDescribedByExpression() {
        $fieldName = $this->attributes->name;
        $errorContainerId = $this->errors->attributes->id;
        $return = "(fieldHasError('$fieldName') ? '$errorContainerId' : '')";
        if ($this->helpText->hasHelpText()) {
            $helpTextContainerId = $this->helpText->attributes->id;
            $return .= "+ ' $helpTextContainerId'";
        }
        return $return;

    }

    /**
     * Returns the Form this Field belongs to.
     *
     * @return Form|null
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Does this Field belong to a Form?
     *
     * @return bool
     */
    public function belongsToForm(): bool
    {
        return !is_null($this->form);
    }

    /**
     * Is this FormControl a Field?
     *
     * @return bool
     */
    public function isAField(): bool
    {
        return array_search(FieldInterface::class, class_implements($this));
    }

    /**
     * Can this FormControl have a help-text?
     *
     * @return bool
     */
    public function canHaveHelpText(): bool
    {
        return array_search(HelpTextTrait::class, class_uses_recursive($this));
    }

    /**
     * Disables vue-functionality for this form-control.
     *
     * @return $this
     */
    public function disableVue()
    {
        $this->vueEnabled = false;
        return $this;
    }

    /**
     * Is vue enabled for this FormControl?
     *
     * @return bool
     */
    public function isVueEnabled(): bool
    {
        if (!config('formfactory.vue.enabled')) {
            return false;
        }

        if ($this->belongsToForm() && !$this->form->is(VueForm::class)) {
            return false;
        }

        return $this->vueEnabled;
    }

    /**
     * Sets an auto-generated default-id.
     */
    protected function setDefaultId()
    {

        // Do not generate ID for Options or Optgroups
        if ($this->is(Option::class) || $this->is(Optgroup::class)) {
            return;
        }

        $this->id(function () {

            $id = '';

            // If this FormControl belongs to a Form,
            // the auto-generated IDs always starts with the ID of the form.
            if ($this->belongsToForm()) {
                $id = $this->getForm()->getId() . '_';
            }

            // If this FormControl has a name-attribute, we append it.
            // Otherwise we use the Element-name.
            if ($this->isAField()) {
                $id .= $this->getFieldName();
            }
            else if ($this->attributes->isSet('name')) {
                $id .= $this->attributes->name;
            } else {
                $id .= $this->getName();
            }

            // For RadioInputs we also append the value.
            if ($this->is(RadioInput::class)) {
                $id .= '_' . $this->attributes->value;
            }

            return $id;
        });

    }

}

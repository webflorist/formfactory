<?php

namespace Nicat\FormFactory\Components\FormControls\Traits;

use Nicat\FormFactory\Components\Form\Form;
use Nicat\FormFactory\Components\Helpers\FieldWrapper;
use Nicat\FormFactory\Components\FormControls\Contracts\FieldInterface;
use Nicat\FormFactory\Components\FormControls\FileInput;
use Nicat\FormFactory\Components\FormControls\Optgroup;
use Nicat\FormFactory\Components\FormControls\Option;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\FormFactory\Exceptions\OpenElementNotFoundException;
use Nicat\FormFactory\FormFactory;
use Nicat\FormFactory\Components\Helpers\ErrorContainer;
use Nicat\FormFactory\Components\Helpers\HelpTextContainer;
use Nicat\FormFactory\Components\Helpers\FieldLabel;
use Nicat\FormFactory\Components\Form\FieldRules\FieldRuleProcessor;
use Nicat\FormFactory\Components\Form\FieldValues\FieldValueProcessor;
use Nicat\HtmlFactory\Elements\ButtonElement;

/**
 * This traits provides basic functionality for FormControls.
 *
 * @package Nicat\FormFactory
 */
trait FormControlTrait
{

    /**
     * The Form this Field belongs to.
     *
     * @var null|Form
     */
    private $form = null;

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

        // Apply view.
        try {
            $this->view('formfactory::form-controls.' . kebab_case((new \ReflectionClass($this))->getShortName()));
        } catch (\ReflectionException $e) {
        }

        // Set a default-ID for various FormControls.
        $this->setDefaultId();

        if ($this->isAField()) {
            $this->wrapper = new FieldWrapper($this);
            $this->errors = new ErrorContainer($this);

            if ($this->canHaveLabel()) {
                $this->label = new FieldLabel($this);
            }
        }

        // Apply help-text.
        if ($this->canHaveHelpText()) {
            $this->helpText = new HelpTextContainer($this);
        }
    }

    /**
     * Performs various tasks before decoration.
     *
     * TODO: This is all very smelly. Find better solution.
     */
    protected function processFormControl()
    {
        if ($this->isAField()) {

            FieldRuleProcessor::process($this);
            FieldValueProcessor::process($this);

            if ($this->canHaveLabel()) {
                $this->label->generate();
            }

            if ($this->isVueEnabled()) {
                $this->applyVueDirectives();
            }

            $this->errors->generate();
        }

        if ($this->canHaveHelpText()) {
            $this->helpText->generate();
        }

        // Auto-translate button-content, if none was set.
        if ($this->is(ButtonElement::class)) {
            if (!$this->content->hasContent()) {
                $this->content(
                    $this->performAutoTranslation(ucwords($this->attributes->name))
                );
            }
        }

    }

    protected function applyVueDirectives()
    {
        $fieldName = $this->attributes->name;
        $fieldBase = "fields['$fieldName']";
        if (!$this->attributes->isSet('v-model') && !$this->is(FileInput::class)) {
            $this->vModel($fieldBase . '.value');
        }
        if (!$this->attributes->isSet('v-bind')) {
            $this->vBind('required', $fieldBase . '.isRequired');
            $this->vBind('disabled', $fieldBase . '.isDisabled');
            $this->vBind('aria-invalid', "fieldHasError('$fieldName')");
        }
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
     * Is vue enabled for this form-control?
     *
     * @return bool
     */
    public function isVueEnabled(): bool
    {
        if (!config('formfactory.vue.enabled')) {
            return false;
        }

        if ($this->belongsToForm() && !$this->form->isVueEnabled()) {
            return false;
        }

        return true;
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
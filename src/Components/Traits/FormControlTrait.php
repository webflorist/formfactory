<?php

namespace Nicat\FormFactory\Components\Traits;

use Nicat\FormFactory\Components\Additional\FieldWrapper;
use Nicat\FormFactory\Components\Contracts\FieldInterface;
use Nicat\FormFactory\Components\Contracts\HelpTextInterface;
use Nicat\FormFactory\Components\FormControls\FileInput;
use Nicat\FormFactory\Components\FormControls\InputGroup;
use Nicat\FormFactory\Components\FormControls\Optgroup;
use Nicat\FormFactory\Components\FormControls\Option;
use Nicat\FormFactory\Components\FormControls\RadioGroup;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\FormFactory\Exceptions\OpenElementNotFoundException;
use Nicat\FormFactory\FormFactory;
use Nicat\FormFactory\Components\Additional\FieldErrors;
use Nicat\FormFactory\Components\Additional\FieldHelpText;
use Nicat\FormFactory\Components\Additional\FieldLabel;
use Nicat\FormFactory\Utilities\FieldRules\FieldRuleProcessor;
use Nicat\FormFactory\Utilities\FieldValues\FieldValueProcessor;
use Nicat\FormFactory\Utilities\Forms\FormInstance;
use Nicat\HtmlFactory\Elements\ButtonElement;

/**
 * This traits provides basic functionality for FormControls.
 *
 * @package Nicat\FormFactory
 */
trait FormControlTrait
{

    /**
     * The FormInstance this Field belongs to.
     *
     * @var null|FormInstance
     */
    private $formInstance = null;

    /**
     * Performs various Setup-tasks for this FormControl.
     *
     * TODO: This is all very smelly. Find better solution.
     */
    protected function setupFormControl()
    {
        // Register the FormControl with the currently open FormInstance.
        try {
            $this->formInstance = FormFactory::singleton()->getOpenForm();
            $this->formInstance->registerFormControl($this);
        } catch (OpenElementNotFoundException $e) {
        }

        // Apply view.
        try {
            $this->view('formfactory::form-controls.' . kebab_case((new \ReflectionClass($this))->getShortName()));
        } catch (\ReflectionException $e) {
        }

        // Set a default-ID for all non-Option and -Optgroup FormControls.
        if (!$this->is(Option::class) && !$this->is(Optgroup::class)) {
            $this->setDefaultId();
        }

        if ($this->isAField()) {
            $this->wrapper = new FieldWrapper($this);
            $this->errors = new FieldErrors($this);

            if ($this->canHaveLabel()) {
                $this->label = new FieldLabel($this);
            }
        }

        // Apply help-text.
        if ($this->canHaveHelpText()) {
            $this->helpText = new FieldHelpText($this);
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

            if (!$this->is(RadioGroup::class) && !$this->is(InputGroup::class)) {
                FieldRuleProcessor::process($this);
                FieldValueProcessor::process($this);

                // Set auto-translation for placeholder.
                if ($this->attributes->isAllowed('placeholder') && !$this->attributes->isSet('placeholder')) {
                    $this->placeholder(function () {
                        $defaultValue = $this->label->hasLabel() ? $this->label->getText() : null;
                        return $this->performAutoTranslation($defaultValue, 'Placeholder');
                    });
                }

                if ($this->canHaveLabel()) {
                    $this->label->generate();
                }
            }

            if ($this->isVueEnabled() && !$this->is(RadioGroup::class) && !$this->is(InputGroup::class)) {
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
     * Returns the FormInstance this Field belongs to.
     *
     * @return FormInstance|null
     */
    public function getFormInstance()
    {
        return $this->formInstance;
    }

    /**
     * Does this Field belong to a FormInstance?
     *
     * @return bool
     */
    public function hasFormInstance(): bool
    {
        return !is_null($this->formInstance);
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
        return array_search(HelpTextInterface::class, class_implements($this));
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

        if ($this->hasFormInstance() && !$this->formInstance->isVueEnabled()) {
            return false;
        }

        return true;
    }

    /**
     * Sets an auto-generated default-id.
     */
    protected function setDefaultId()
    {
        $this->id(function () {

            $id = '';

            // If this FormControl belongs to a FormInstance,
            // the auto-generated IDs always starts with the ID of the form.
            if ($this->hasFormInstance()) {
                $id = $this->getFormInstance()->getId() . '_';
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
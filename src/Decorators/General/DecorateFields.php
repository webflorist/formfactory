<?php

namespace Nicat\FormFactory\Decorators\General;

use Nicat\FormFactory\Components\Additional\FieldWrapper;
use Nicat\FormFactory\FormFactory;
use Nicat\FormFactory\Utilities\ComponentLists;
use Nicat\FormFactory\Utilities\FieldRules\FieldRuleProcessor;
use Nicat\FormFactory\Utilities\FieldValues\FieldValueProcessor;
use Nicat\FormFactory\Components\FormControls\HiddenInput;
use Nicat\FormFactory\Components\FormControls\Option;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\FormFactory\Components\Traits\CanHaveHelpText;
use Nicat\FormFactory\Components\Traits\CanHaveLabel;
use Nicat\FormFactory\Components\Traits\UsesAutoTranslation;
use Nicat\FormFactory\Utilities\FormFactoryTools;
use Nicat\HtmlFactory\Decorators\Abstracts\Decorator;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Attributes\Traits\AllowsPlaceholderAttribute;

/**
 * Apply various decorations to FormFactory-fields.
 *
 * Class DecorateFields
 * @package Nicat\FormFactory\Decorators\General
 */
class DecorateFields extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var Element|CanHaveLabel|UsesAutoTranslation|AllowsPlaceholderAttribute|CanHaveHelpText
     */
    protected $element;

    /**
     * Returns the group-ID of this decorator.
     *
     * Returning null means this decorator will always be applied.
     *
     * @return string|null
     */
    public static function getGroupId()
    {
        return null;
    }

    /**
     * Returns an array of class-names of elements, that should be decorated by this decorator.
     *
     * @return string[]
     */
    public static function getSupportedElements(): array
    {
        return ComponentLists::fields();
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {

        // Apply laravel-rules to the field's attributes for browser-live-validation.
        $this->applyRules();

        // Applies default- or submitted-values to the field.
        $this->applyValues();

        // Automatically generate the label-text for fields without a manually set label using auto-translation.
        $this->autoGenerateLabelText();

        // Automatically generate the placeholder-text for fields without a manually set placeholder using auto-translation.
        $this->autoGeneratePlaceholder();

        // Automatically generate help-texts for fields without a manually set help-text using auto-translation.
        $this->autoGenerateHelpText();

        //Wrap fields with the FieldWrapper.
        $this->autoPopulateErrors();

        //Wrap fields with the FieldWrapper.
        $this->applyAriaTagsOnErrors();

        $this->addAriaDescribedByOnHelpText();

    }

    /**
     * Wrap fields with the FieldWrapper.
     */
    protected function applyFieldWrapper()
    {
        // For hidden-input-fields, a FieldWrapper does not make sense.
        if ($this->element->is(HiddenInput::class)) {
            return;
        }

        // If wrapper is specifically set to false, we do not apply one.
        if ($this->element->wrapper === false) {
            return;
        }

        if (is_null($this->element->wrapper)) {
            $this->element->wrap(
                new FieldWrapper($this->element)
            );
        }
    }

    /**
     * Applies laravel-rules to the field's attributes for browser-live-validation using the RulesProcessor.
     */
    private function applyRules()
    {
        if (method_exists($this->element,'rules')) {
            FieldRuleProcessor::process($this->element);
        }
    }

    /**
     * Applies default- or submitted-values to the field using the FieldValueProcessor.
     */
    private function applyValues()
    {
        // Options are handled via their Select.
        if ($this->element->is(Option::class)) {
            return;
        }

        FieldValueProcessor::process($this->element);
    }

    /**
     * Automatically generates the label-text for fields without a manually set label using auto-translation.
     */
    protected function autoGenerateLabelText()
    {
        if (method_exists($this->element,'label') && is_null($this->element->label)) {
            $defaultValue = ucwords(FormFactoryTools::arrayStripString($this->element->attributes->name));
            if ($this->element->is(RadioInput::class)) {
                $defaultValue = ucwords($this->element->attributes->value);
            }
            $this->element->label(
                $this->element->performAutoTranslation($defaultValue)
            );
        }
    }

    /**
     * Automatically generates the placeholder-text for fields without a manually set placeholder using auto-translation.
     */
    private function autoGeneratePlaceholder()
    {
        if ($this->element->attributes->isAllowed('placeholder') && !$this->element->attributes->isSet('placeholder')) {
            $defaultValue = $this->element->label ? $this->element->label->getText() : null;
            $this->element->placeholder(
                $this->element->performAutoTranslation($defaultValue,'Placeholder')
            );
        }
    }

    /**
     * Automatically generates help-texts for fields without a manually set help-text using auto-translation.
     */
    protected function autoGenerateHelpText()
    {
        if (method_exists($this->element,'helpText') && ($this->element->helpText === null)) {
            $helpText = $this->element->performAutoTranslation(null, 'HelpText');
            if ($helpText !== null) {
                $this->element->helpText($helpText);
            }
        }
    }

    /**
     * Tries to retrieves errors from the FormInstance, if no errors have been set yet.
     */
    private function autoPopulateErrors()
    {
        if ($this->element->errors === null) {
            $this->element->errors(
                $this->element->getFormInstance()->errors->getErrorsForField($this->element->attributes->name)
            );
        }
    }

    private function applyAriaTagsOnErrors()
    {
        if ($this->element->errors) {
            $this->element->addAriaDescribedby($this->element->attributes->id . '_errors');
            $this->element->ariaInvalid();
        }
    }

    private function addAriaDescribedByOnHelpText()
    {
        if ($this->element->helpText) {
            $this->element->addAriaDescribedby($this->element->attributes->id . '_helpText');
        }
    }

}
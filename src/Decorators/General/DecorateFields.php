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
        // Automatically generate a meaningful id for fields without a manually set id.
        $this->autoGenerateID();

        //Wrap fields with the FieldWrapper.
        $this->applyFieldWrapper();

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

    }

    /**
     * Automatically generates a meaningful id for fields without a manually set id.
     */
    protected function autoGenerateID()
    {
        // If the element already has an id, we leave it be.
        if ($this->element->attributes->isSet('id')) {
            return;
        }

        // If $this->element has no 'name' attribute set, we abort,
        // because without a name we can not auto-create an id.
        if (!$this->element->attributes->isSet('name')) {
            return;
        }

        // Auto-generated IDs always start with formID...
        $fieldId = FormFactory::singleton()->getOpenForm()->getId();

        // ...followed by the field-name.
        $fieldId .= '_' . $this->element->attributes->name;

        // For radio-buttons and options we also append the value.
        if ($this->element->is(RadioInput::class)) {
            $fieldId .= '_' . $this->element->attributes->value;
        }

        $this->element->id($fieldId);
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
            $defaultValue = $this->element->label;
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
        if (method_exists($this->element,'hasHelpText') && ($this->element->getHelpText() === null)) {
            $helpText = $this->element->performAutoTranslation(null, 'HelpText');
            if ($helpText !== null) {
                $this->element->helpText($helpText);
            }
        }
    }

}
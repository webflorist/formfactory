<?php

namespace Nicat\FormBuilder\Decorators\General;

use Nicat\FormBuilder\Components\Additional\FieldWrapper;
use Nicat\FormBuilder\Components\FormControls\MonthInput;
use Nicat\FormBuilder\Components\FormControls\PasswordInput;
use Nicat\FormBuilder\Components\FormControls\RangeInput;
use Nicat\FormBuilder\Components\FormControls\SearchInput;
use Nicat\FormBuilder\Components\FormControls\TelInput;
use Nicat\FormBuilder\Components\FormControls\TimeInput;
use Nicat\FormBuilder\Components\FormControls\UrlInput;
use Nicat\FormBuilder\Components\FormControls\WeekInput;
use Nicat\FormBuilder\FormBuilder;
use Nicat\FormBuilder\Utilities\FieldRules\FieldRuleProcessor;
use Nicat\FormBuilder\Utilities\FieldValues\FieldValueProcessor;
use Nicat\FormBuilder\Components\FormControls\CheckboxInput;
use Nicat\FormBuilder\Components\FormControls\ColorInput;
use Nicat\FormBuilder\Components\FormControls\DateInput;
use Nicat\FormBuilder\Components\FormControls\DatetimeInput;
use Nicat\FormBuilder\Components\FormControls\DatetimeLocalInput;
use Nicat\FormBuilder\Components\FormControls\EmailInput;
use Nicat\FormBuilder\Components\FormControls\FileInput;
use Nicat\FormBuilder\Components\FormControls\HiddenInput;
use Nicat\FormBuilder\Components\FormControls\NumberInput;
use Nicat\FormBuilder\Components\FormControls\Option;
use Nicat\FormBuilder\Components\FormControls\RadioInput;
use Nicat\FormBuilder\Components\FormControls\Select;
use Nicat\FormBuilder\Components\FormControls\Textarea;
use Nicat\FormBuilder\Components\FormControls\TextInput;
use Nicat\FormBuilder\Components\Traits\CanHaveHelpText;
use Nicat\FormBuilder\Components\Traits\CanHaveLabel;
use Nicat\FormBuilder\Components\Traits\UsesAutoTranslation;
use Nicat\FormBuilder\Utilities\FormBuilderTools;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;
use Nicat\HtmlBuilder\Elements\Abstracts\Element;
use Nicat\HtmlBuilder\Attributes\Traits\AllowsPlaceholderAttribute;

/**
 * Apply various decorations to FormBuilder-fields.
 *
 * Class DecorateFields
 * @package Nicat\FormBuilder\Decorators\General
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
     * Returns an array of frontend-framework-ids, this decorator is specific for.
     * Returning an empty array means all frameworks are supported.
     *
     * @return string[]
     */
    public static function getSupportedFrameworks(): array
    {
        return [];
    }

    /**
     * Returns an array of class-names of elements, that should be decorated by this decorator.
     *
     * @return string[]
     */
    public static function getSupportedElements(): array
    {
        return [
            CheckboxInput::class,
            ColorInput::class,
            DateInput::class,
            DatetimeInput::class,
            DatetimeLocalInput::class,
            EmailInput::class,
            FileInput::class,
            HiddenInput::class,
            MonthInput::class,
            NumberInput::class,
            PasswordInput::class,
            RadioInput::class,
            RangeInput::class,
            SearchInput::class,
            Select::class,
            TelInput::class,
            Textarea::class,
            TextInput::class,
            TimeInput::class,
            UrlInput::class,
            WeekInput::class
        ];
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

        $fieldId = '';

        // Auto-generated IDs always start with formID...
        if (!is_null(app(FormBuilder::class)->openForm)) {
            $fieldId .= app(FormBuilder::class)->openForm->attributes->id . '_';
        }

        // ...followed by the field-name.
        $fieldId .= $this->element->attributes->name;

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
            $defaultValue = ucwords(FormBuilderTools::arrayStripString($this->element->attributes->name));
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
        if (method_exists($this->element,'hasHelpText') && !$this->element->hasHelpText()) {
            $helpText = $this->element->performAutoTranslation(null, 'HelpText');
            if ($helpText !== null) {
                $this->element->helpText($helpText);
            }
        }
    }

}
<?php

namespace Nicat\FormFactory\Decorators\General;

use Nicat\FormFactory\Components\Contracts\AutoTranslationInterface;
use Nicat\FormFactory\Components\Contracts\FieldInterface;
use Nicat\FormFactory\Components\Contracts\FormControlInterface;
use Nicat\FormFactory\Components\Contracts\HelpTextInterface;
use Nicat\FormFactory\Components\Contracts\LabelInterface;
use Nicat\FormFactory\Components\FormControls\FileInput;
use Nicat\FormFactory\Components\Traits\AutoTranslationTrait;
use Nicat\FormFactory\Components\Traits\FieldTrait;
use Nicat\FormFactory\Components\Traits\FormControlTrait;
use Nicat\FormFactory\Components\Traits\HelpTextTrait;
use Nicat\FormFactory\Components\Traits\LabelTrait;
use Nicat\FormFactory\Utilities\ComponentLists;
use Nicat\FormFactory\Utilities\Config\FormFactoryConfig;
use Nicat\FormFactory\Utilities\FieldRules\FieldRuleProcessor;
use Nicat\FormFactory\Utilities\FieldValues\FieldValueProcessor;
use Nicat\FormFactory\Components\FormControls\Option;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\FormFactory\Utilities\FormFactoryTools;
use Nicat\HtmlFactory\Decorators\Abstracts\Decorator;
use Nicat\HtmlFactory\Elements\Abstracts\Element;

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
     * @var Element|FieldInterface|FormControlInterface|HelpTextInterface|LabelInterface|FieldTrait|FormControlTrait|HelpTextTrait|LabelTrait|AutoTranslationInterface|AutoTranslationTrait
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
        $this->autoGenerateId();

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

        if (FormFactoryConfig::isVueEnabled()) {
            $this->applyVueDirectives();
        }
        else {
            $this->applyAriaTagsOnErrors();
            $this->addAriaDescribedByOnHelpText();
        }

    }

    /**
     * Automatically generates a meaningful id for the field.
     */
    private function autoGenerateId()
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

        // The Auto-generated IDs always start with formID...
        $fieldId = '';
        if ($this->element->hasFormInstance()) {
            $fieldId = $this->element->getFormInstance()->getId();
        }

        // ...followed by the field-name.
        $fieldId .= '_' . $this->element->attributes->name;

        // For radio-buttons we also append the value.
        if ($this->element->is(RadioInput::class)) {
            $fieldId .= '_' . $this->element->attributes->value;
        }

        $this->element->id($fieldId);
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
        if ($this->element->canHaveLabel() && ! $this->element->label->hasLabel()) {
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
            $defaultValue = $this->element->label->hasLabel() ? $this->element->label->getText() : null;
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
        if ($this->element->canHaveHelpText() && !$this->element->helpText->hasHelpText()) {
            $helpText = $this->element->performAutoTranslation(null, 'HelpText');
            if ($helpText !== null) {
                $this->element->helpText($helpText);
            }
        }
    }

    private function applyAriaTagsOnErrors()
    {
        if ($this->element->errors->hasErrors()) {
            $this->element->addAriaDescribedby($this->element->errors->getContainerId());
            $this->element->ariaInvalid();
        }
    }

    private function addAriaDescribedByOnHelpText()
    {
        if ($this->element->canHaveHelpText() && $this->element->helpText->hasHelpText()) {
            $this->element->addAriaDescribedby($this->element->helpText->getContainerId());
        }
    }

    private function applyVueDirectives()
    {
        $fieldName = $this->element->attributes->name;
        $fieldBase = "fields['$fieldName']";
        if (!$this->element->attributes->isSet('v-model') && !$this->element->is(FileInput::class)) {
            $this->element->vModel($fieldBase . '.value');
        }
        if (!$this->element->attributes->isSet('v-bind')) {
            $this->element->vBind('required',$fieldBase . '.isRequired');
            $this->element->vBind('disabled',$fieldBase . '.isDisabled');
            $this->element->vBind('aria-invalid',"fieldHasError('$fieldName')");
        }
    }

}
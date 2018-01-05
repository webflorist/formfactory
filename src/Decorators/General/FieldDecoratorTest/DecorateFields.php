<?php

namespace Nicat\FormBuilder\Decorators\General;

use Nicat\FormBuilder\Components\ErrorWrapper;
use Nicat\FormBuilder\Components\FieldWrapper;
use Nicat\FormBuilder\Elements\ButtonElement;
use Nicat\FormBuilder\Elements\CheckboxInputElement;
use Nicat\FormBuilder\Elements\ColorInputElement;
use Nicat\FormBuilder\Elements\DateInputElement;
use Nicat\FormBuilder\Elements\DatetimeInputElement;
use Nicat\FormBuilder\Elements\DatetimeLocalInputElement;
use Nicat\FormBuilder\Elements\EmailInputElement;
use Nicat\FormBuilder\Elements\FileInputElement;
use Nicat\FormBuilder\Elements\HelpTextElement;
use Nicat\FormBuilder\Elements\HiddenInputElement;
use Nicat\FormBuilder\Elements\NumberInputElement;
use Nicat\FormBuilder\Elements\OptionElement;
use Nicat\FormBuilder\Elements\RadioInputElement;
use Nicat\FormBuilder\Elements\ResetButtonElement;
use Nicat\FormBuilder\Elements\SelectElement;
use Nicat\FormBuilder\Elements\SubmitButtonElement;
use Nicat\FormBuilder\Elements\TextareaElement;
use Nicat\FormBuilder\Elements\TextInputElement;
use Nicat\FormBuilder\Elements\Traits\CanHaveHelpText;
use Nicat\FormBuilder\Elements\Traits\CanHaveLabel;
use Nicat\FormBuilder\Elements\Traits\UsesAutoTranslation;
use Nicat\FormBuilder\FormBuilder;
use Nicat\FormBuilder\FormBuilderTools;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;
use Nicat\HtmlBuilder\Elements\Abstracts\ContainerElement;
use Nicat\HtmlBuilder\Elements\Abstracts\Element;
use Nicat\HtmlBuilder\Elements\Abstracts\InputElement;
use Nicat\HtmlBuilder\Elements\LabelElement;
use Nicat\HtmlBuilder\Elements\Traits\AllowsAcceptAttribute;
use Nicat\HtmlBuilder\Elements\Traits\AllowsCheckedAttribute;
use Nicat\HtmlBuilder\Elements\Traits\AllowsPatternAttribute;
use Nicat\HtmlBuilder\Elements\Traits\AllowsPlaceholderAttribute;
use Nicat\HtmlBuilder\Elements\Traits\AllowsTypeAttribute;
use Nicat\HtmlBuilder\Elements\Traits\AllowsValueAttribute;

/**
 * Automatically generates an id for fields without a manually set id.
 * The generated id will be:
 *
 * Class AutoGenerateFieldIDs
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
            TextInputElement::class,
            NumberInputElement::class,
            ColorInputElement::class,
            DateInputElement::class,
            DatetimeInputElement::class,
            DatetimeLocalInputElement::class,
            EmailInputElement::class,
            HiddenInputElement::class,
            CheckboxInputElement::class,
            FileInputElement::class,
            RadioInputElement::class,
            TextareaElement::class,
            SelectElement::class,
            OptionElement::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {

        $this->autoGenerateID();

        $this->applyFieldWrapper();

        $this->applyRules();

        $this->applyValues();

        $this->autoGenerateLabelText();

        $this->indicateRequiredFields();

        $this->autoGeneratePlaceholder();

        $this->applyLabel();

        $this->autoGenerateHelpText();
        
        $this->applyHelpText();

        $this->applyErrors();

    }

    protected function autoGenerateID()
    {
        // If the element already has an id, we leave it be.
        if ($this->element->attributes->isSet('id')) {
            return;
        }

        // The field-element containing the field-name is always $this->element,
        // except with option-elements, where we use the currently open select-element.
        $fieldElement = $this->element;
        if ($this->element->is(OptionElement::class)) {
            $fieldElement = $this->formBuilder->openSelect;
        }

        // If $fieldElement has no 'name' attribute set, we abort,
        // because without a name we can not auto-create an id.
        if (!$fieldElement->attributes->isSet('name')) {
            return;
        }

        // Auto-generated IDs always start with formID...
        $fieldId = $this->formBuilder->openForm->attributes->getValue('id');

        // ...followed by the field-name.
        $fieldId .= '_' . $fieldElement->attributes->getValue('name');

        // For radio-buttons and options we also append the value.
        if ($this->element->is(RadioInputElement::class) || $this->element->is(OptionElement::class)) {
            $fieldId .= '_' . $this->element->attributes->getValue('value');
        }

        $this->element->id($fieldId);
    }

    protected function applyFieldWrapper()
    {
        if (is_null($this->element->wrapper)) {
            $this->element->wrap(
                new FieldWrapper($this->element)
            );
        }
    }

    protected function autoGenerateLabelText()
    {
        if (method_exists($this->element,'label') && is_null($this->element->label)) {
            $defaultValue = ucwords(FormBuilderTools::arrayStripString($this->element->attributes->getValue('name')));
            if ($this->element->is(RadioInputElement::class)) {
                $defaultValue = ucwords($this->element->attributes->getValue('value'));
            }
            $this->element->label(
                $this->element->performAutoTranslation(null, '', $defaultValue)
            );
        }
    }

    private function autoGeneratePlaceholder()
    {
        if ($this->element->attributes->isAllowed('placeholder') && !$this->element->attributes->isSet('placeholder')) {
            $defaultValue = ucwords(FormBuilderTools::arrayStripString($this->element->attributes->getValue('name')));
            $this->element->placeholder(
                $this->element->performAutoTranslation(null,'Placeholder',$defaultValue)
            );
        }
    }

    protected function applyLabel()
    {
        // If the element does not want a label, we have nothing to do.
        if (!method_exists($this->element,'label') || $this->element->labelMode === 'none') {
            return;
        }

        // Create the label-element with the label-text as it's content.
        $label = (new LabelElement())->content($this->element->label);

        // If labelMode is set to 'bound', we simply wrap the field with the label,
        // and replace the field-element with the label-element in $this.

        if ($this->element->labelMode === 'bound') {
            if (!is_null($this->element->wrapper)) {
                $label->wrap($this->element->wrapper);
            }
            $this->element->wrap($label);
            return;
        }

        // In all other cases the label-element should have the for-attribute
        // pointing to the field's id.
        $label->for($this->element->attributes->id);

        // If labelMode is set to 'after', we append the label after the field.
        if ($this->element->labelMode === 'after') {
            $this->element->insertAfter($label);
            return;
        }

        // If labelMode is set to 'sr-only', we add the 'sr-only' class to the label.
        if ($this->element->labelMode === 'sr-only') {
            $label->addClass('sr-only');
        }

        // Standard-procedure is to insert the label before the field.
        $this->element->insertBefore($label);
    }

    protected function autoGenerateHelpText()
    {
        if (method_exists($this->element,'hasHelpText') && !$this->element->hasHelpText()) {
            $helpText = $this->element->performAutoTranslation(null, 'HelpText');
            if ($helpText !== null) {
                $this->element->helpText($helpText);
            }
        }
    }

    private function applyHelpText()
    {
        if (method_exists($this->element,'hasHelpText') && $this->element->hasHelpText()) {

            // The ID of the HelpTextElement should be the id of the field plus the suffix '_helpText'.
            $helpTextElementId = $this->element->attributes->getValue('id') . '_helpText';

            // Create HelpTextElement.
            $helpTextElement = (new HelpTextElement())
                ->content($this->element->getHelpText())
                ->id($helpTextElementId);

            // Add the help-text-element according to it's desired location.
            if ($this->element->helpTextLocation === 'append') {
                $this->element->appendChild($helpTextElement);
            }
            if ($this->element->helpTextLocation === 'prepend') {
                $this->element->prependChild($helpTextElement);
            }
            if ($this->element->helpTextLocation === 'after') {
                $this->element->insertAfter($helpTextElement);
            }
            if ($this->element->helpTextLocation === 'before') {
                $this->element->insertBefore($helpTextElement);
            }

            // Add the 'aria-describedby' attribute to the field-element.
            $this->element->addAriaDescribedby($helpTextElementId);
        }
    }

    protected function indicateRequiredFields()
    {
        if (!$this->element->is(RadioInputElement::class) && method_exists($this->element,'label')) {
            if (!is_null($this->element->label) && $this->element->attributes->isSet('required')) {
                $this->element->label(
                    $this->element->label . '<sup>*</sup>'
                );
            }
        }
    }

    /**
     * Perform decorations on $this->element.
     */
    public function applyRules()
    {
        if (method_exists($this->element,'hasRules') && $this->element->hasRules()) {
            foreach ($this->element->getRules() as $rule => $parameters) {
                $applyRulesMethod = 'apply' . studly_case($rule) . 'Rule';
                if (method_exists($this, $applyRulesMethod)) {
                    call_user_func([$this,$applyRulesMethod], $parameters);
                }
            }
        }
    }

    /**
     * Applies 'required' rule.
     */
    private function applyRequiredRule()
    {
        /** @var AllowsRequiredAttribute $element */
        $element = $this->element;
        $element->required();
    }

    /**
     * Applies 'accepted' rule.
     *
     */
    private function applyAcceptedRule()
    {
        $this->applyRequiredRule();
    }

    /**
     * Applies 'not_numeric' rule.
     */
    private function applyNotNumericRule()
    {
        $this->applyPatternAttribute('\D+');
    }

    /**
     * Applies 'url' rule.
     */
    private function applyUrlRule()
    {
        /** @var AllowsTypeAttribute $element */
        $element = $this->element;
        $element->type('url');
    }

    /**
     * Applies 'active_url' rule.
     */
    private function applyActiveUrlRule()
    {
        $this->applyUrlRule();
    }

    /**
     * Applies 'alpha' rule.
     */
    private function applyAlphaRule()
    {
        $this->applyPatternAttribute('[a-zA-Z]+');
    }

    /**
     * Applies 'alpha_dash' rule.
     */
    private function applyAlphaDashRule()
    {
        $this->applyPatternAttribute('[a-zA-Z0-9_\-]+');
    }

    /**
     * Applies 'alpha_num' rule.
     *
     */
    private function applyAlphaNumRule()
    {
        $this->applyPatternAttribute('[a-zA-Z0-9]+');
    }

    /**
     * Applies 'between' rule.
     *
     * @param array $parameters
     */
    private function applyBetweenRule(array $parameters)
    {

        // For number-inputs we apply a min- and max-attributes.
        if ($this->element->is(InputElement::class) && ($this->element->attributes->getValue('type') === 'number')) {
            $this->applyMinAttribute($parameters[0]);
            $this->applyMaxAttribute($parameters[1]);
            return;
        }

        // For all others, we apply pattern- and maxlength-attributes.
        $this->applyPatternAttribute('.{' . $parameters[0] . ',' . $parameters[1] . '}',true);
        $this->applyMaxlengthAttribute($parameters[1]);

    }

    /**
     * Applies 'in' rule.
     *
     * @param array $parameters
     */
    private function applyInRule(array $parameters)
    {
        $parameters = (sizeof($parameters) == 1) ? $parameters[0] : '(' . join('|', $parameters) . ')';
        $this->applyPatternAttribute('^' . $parameters . '$');
    }

    /**
     * Applies 'required' rule.
     *
     * @param array $parameters
     */
    private function applyMaxRule(array $parameters)
    {

        // For number-inputs we apply a max-attribute.
        if ($this->element->is(InputElement::class) && ($this->element->attributes->getValue('type') === 'number')) {
            $this->applyMaxAttribute($parameters[0]);
            return;
        }

        // For all others we apply the maxlength attribute.
        $this->applyMaxlengthAttribute($parameters[0]);

    }

    /**
     * Applies 'min' rule.
     *
     * @param array $parameters
     */
    private function applyMinRule(array $parameters)
    {

        // For number-inputs we apply a min-attribute.
        if ($this->element->is(InputElement::class) && ($this->element->attributes->getValue('type') === 'number')) {
            $this->applyMinAttribute($parameters[0]);
            return;
        }

        // For all others we apply the pattern attribute.
        $this->applyPatternAttribute(".{" . $parameters[0] . ",}", true);
    }

    /**
     * Applies 'not_in' rule.
     *
     * @param array $parameters
     */
    private function applyNotInRule(array $parameters)
    {
        $this->applyPatternAttribute('(?:(?!^' . join('$|^', $parameters) . '$).)*');
    }

    /**
     * Applies 'numeric' rule.
     *
     * @param array $parameters
     */
    private function applyNumericRule(array $parameters)
    {
        /** @var AllowsTypeAttribute $element */
        $element = $this->element;
        $element->type('number');
        $this->applyPatternAttribute('[+-]?\d*\.?\d+');
    }

    /**
     * Applies 'mimes' rule.
     *
     * @param array $parameters
     */
    private function applyMimesRule(array $parameters)
    {
        /** @var AllowsAcceptAttribute $element */
        $element = $this->element;
        if (array_search('jpeg', $parameters) !== false) {
            array_push($parameters, 'jpg');
        }
        $element->accept('.' . implode(',.', $parameters));
    }

    /**
     * Applies a pattern-attribute.
     *
     * @param string $pattern
     * @param bool $append
     */
    private function applyPatternAttribute(string $pattern, $append = false)
    {
        if ($this->element->attributes->isAllowed('pattern')) {

            /** @var AllowsPatternAttribute $element */
            $element = $this->element;

            // Append to existing pattern, if $append=true.
            if ($append && $element->attributes->isSet('pattern')) {
                $pattern = $element->attributes->getValue('pattern') . $pattern;
            }

            $element->pattern($pattern);

        }
    }

    /**
     * Applies a maxlength-attribute.
     *
     * @param string $maxlength
     */
    private function applyMaxlengthAttribute(string $maxlength)
    {
        if ($this->element->attributes->isAllowed('maxlength')) {
            /** @var AllowsMaxlengthAttribute $element */
            $element = $this->element;
            $element->maxlength($maxlength);
        }
    }

    /**
     * Applies a max-attribute.
     *
     * @param int $value
     */
    private function applyMaxAttribute(int $value)
    {
        if ($this->element->attributes->isAllowed('max')) {
            /** @var AllowsMaxAttribute $element */
            $element = $this->element;
            $element->max($value);
        }
    }

    /**
     * Applies a min-attribute.
     *
     * @param int $value
     */
    private function applyMinAttribute(int $value)
    {
        if ($this->element->attributes->isAllowed('min')) {
            /** @var AllowsMinAttribute $element */
            $element = $this->element;
            $element->min($value);
        }
    }



    public function applyValues()
    {

        $fieldName = $this->element->attributes->getValue('name');

        if (!$this->formBuilder->openForm->wasSubmitted && $this->formBuilder->openForm->fieldHasDefaultValue($fieldName)) {
            $this->applyFieldValue($this->formBuilder->openForm->getDefaultValueForField($fieldName));
        }

        if ($this->formBuilder->openForm->wasSubmitted && $this->formBuilder->openForm->fieldHasSubmittedValue($fieldName)) {
            $this->applyFieldValue($this->formBuilder->openForm->getSubmittedValueForField($fieldName));
        }

    }

    /**
     * Applies $value to $this->element.
     *
     * @param $value
     */
    private function applyFieldValue($value)
    {

        // Apply default-value as content for text-areas.
        if ($this->element->is(TextareaElement::class)) {
            $this->applyContent($value);
            return;
        }

        // Apply default-value as checked-state for checkboxes and radio-buttons.
        if ($this->element->is(CheckboxInputElement::class) || $this->element->is(RadioInputElement::class)) {
            $this->applyCheckedState($value);
            return;
        }

        // All other elements get the default value set as their 'value' attribute.
        $this->applyValue($value);
    }

    /**
     * Applies a default-value as content.
     *
     * @param $defaultValue
     */
    protected function applyContent(string $defaultValue)
    {
        /** @var ContainerElement $containerElement */
        $containerElement = $this->element;
        $containerElement->clearContent();
        $containerElement->content($defaultValue);
    }

    /**
     * Applies a default-value to element's checked-state.
     *
     * @param $defaultValue
     */
    protected function applyCheckedState($defaultValue)
    {
        /** @var AllowsCheckedAttribute $element */
        $element = $this->element;
        $element->checked(
            $defaultValue === $this->element->attributes->getValue('value')
        );
    }

    /**
     * Applies a default-value as the element's 'value'-attribute.
     *
     * @param $defaultValue
     */
    protected function applyValue($defaultValue)
    {
        /** @var AllowsValueAttribute $element */
        $element = $this->element;
        $element->value($defaultValue);
    }

    private function applyErrors()
    {

        if ($this->element->hasErrors() && $this->element->showErrors) {

            $errorWrapper = new ErrorWrapper();

            $errorWrapper->addErrorField($this->element);

            // Add the errorWrapper according to the desired location.
            if ($this->element->errorsLocation === 'append') {
                $this->element->appendChild($errorWrapper);
            }
            if ($this->element->errorsLocation === 'prepend') {
                $this->element->prependChild($errorWrapper);
            }
            if ($this->element->errorsLocation === 'after') {
                $this->element->insertAfter($errorWrapper);
                $this->insertChildAfter(
                    $errorWrapper,
                    $this->element
                );
            }
            if ($this->element->errorsLocation === 'before') {
                $this->element->insertBefore($errorWrapper);
            }

            // Add error-class to wrapper.
            // TODO: make adjustable via decorators.
            $this->element->wrapper->addClass('has-error');
        }
    }
}
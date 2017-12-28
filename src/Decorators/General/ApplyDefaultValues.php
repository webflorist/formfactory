<?php

namespace Nicat\FormBuilder\Decorators\General;

use Nicat\FormBuilder\Elements\CheckboxInputElement;
use Nicat\FormBuilder\Elements\ColorInputElement;
use Nicat\FormBuilder\Elements\DateInputElement;
use Nicat\FormBuilder\Elements\DatetimeInputElement;
use Nicat\FormBuilder\Elements\DatetimeLocalInputElement;
use Nicat\FormBuilder\Elements\EmailInputElement;
use Nicat\FormBuilder\Elements\FileInputElement;
use Nicat\FormBuilder\Elements\FormElement;
use Nicat\FormBuilder\Elements\HiddenInputElement;
use Nicat\FormBuilder\Elements\NumberInputElement;
use Nicat\FormBuilder\Elements\RadioInputElement;
use Nicat\FormBuilder\Elements\TextInputElement;
use Nicat\FormBuilder\FormBuilder;
use Nicat\FormBuilder\Elements\TextareaElement;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;
use Nicat\HtmlBuilder\Elements\Abstracts\ContainerElement;
use Nicat\HtmlBuilder\Elements\Traits\AllowsCheckedAttribute;
use Nicat\HtmlBuilder\Elements\Traits\AllowsValueAttribute;

/**
 * Applies default-values, that were set via the 'values' method on the FormElement.
 *
 * Class ApplyDefaultValues
 * @package Nicat\FormBuilder\Decorators\General
 */
class ApplyDefaultValues extends Decorator
{

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
            FileInputElement::class,
            HiddenInputElement::class,
            CheckboxInputElement::class,
            RadioInputElement::class,
            TextareaElement::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        /** @var FormElement $openForm */
        $openForm = app(FormBuilder::class)->openForm;

        $fieldName = $this->element->attributes->getValue('name');

        // We only apply default-values, if the current form was not submitted.
        if (!$openForm->wasSubmitted && $openForm->fieldHasDefaultValue($fieldName)) {
            $defaultValue = $openForm->getDefaultValueForField($fieldName);

            // Apply default-value as content for text-areas.
            if ($this->element->is(TextareaElement::class)) {
                $this->applyContent($defaultValue);
                return;
            }

            // Apply default-value as checked-state for checkboxes and radio-buttons.
            if ($this->element->is(CheckboxInputElement::class) || $this->element->is(RadioInputElement::class)) {
                $this->applyCheckedState($defaultValue);
                return;
            }

            // All other elements get the default value set as their 'value' attribute.
            $this->applyValue($defaultValue);
            return;
        }
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


}
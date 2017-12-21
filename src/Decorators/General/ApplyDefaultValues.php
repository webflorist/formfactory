<?php

namespace Nicat\FormBuilder\Decorators\General;

use Nicat\FormBuilder\Elements\FormElement;
use Nicat\FormBuilder\Elements\NumberInputElement;
use Nicat\FormBuilder\Elements\TextInputElement;
use Nicat\FormBuilder\FormBuilder;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;
use Nicat\HtmlBuilder\Elements\Abstracts\Element;
use Nicat\HtmlBuilder\Elements\TextareaElement;

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
            TextareaElement::class
        ];
    }

    /**
     * Decorates the element.
     *
     * @param Element $element
     */
    public static function decorate(Element $element)
    {
        /** @var FormElement $currentForm */
        $currentForm = app(FormBuilder::class)->currentForm;

        $fieldName = $element->attributes->getValue('name');

        // We only apply default-values, if the current form was not submitted.
        if (!$currentForm->wasSubmitted && $currentForm->fieldHasDefaultValue($fieldName)) {
            $defaultValue = $currentForm->getDefaultValueForField($fieldName);

            if (is_a($element,TextareaElement::class)) {
                $element->clearContent();
                $element->content($defaultValue);
            }
            else {
                $element->value($defaultValue);
            }
        }
    }


}
<?php

namespace Nicat\FormBuilder\Decorators\General;

use Nicat\FormBuilder\Elements\NumberInputElement;
use Nicat\FormBuilder\Elements\TextareaElement;
use Nicat\FormBuilder\Elements\TextInputElement;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;
use Nicat\HtmlBuilder\Elements\Abstracts\Element;

/**
 * Automatically generates the label-text for fields without a manually set label.
 * Uses auto-translation.
 *
 * Class AutoGenerateFieldIDs
 * @package Nicat\FormBuilder\Decorators\General
 */
class AutoGenerateLabels extends Decorator
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
        if (is_null($element->label)) {
            $element->label(
                ucwords($element->attributes->getValue('name'))
            );
        }
    }
}
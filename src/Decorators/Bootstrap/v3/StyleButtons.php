<?php

namespace Nicat\FormBuilder\Decorators\Bootstrap\v3;

use Nicat\FormBuilder\Elements\ButtonElement;
use Nicat\FormBuilder\Elements\ResetButtonElement;
use Nicat\FormBuilder\Elements\SubmitButtonElement;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;
use Nicat\HtmlBuilder\Elements\Abstracts\Element;

class StyleButtons extends Decorator
{

    /**
     * Returns an array of frontend-framework-ids, this decorator is specific for.
     *
     * @return string[]
     */
    public static function getSupportedFrameworks(): array
    {
        return [
            'bootstrap:3'
        ];
    }

    /**
     * Returns an array of class-names of elements, that should be decorated by this decorator.
     *
     * @return string[]
     */
    public static function getSupportedElements(): array
    {
        return [
            ButtonElement::class,
            SubmitButtonElement::class,
            ResetButtonElement::class
        ];
    }

    /**
     * Decorates the element.
     *
     * @param Element $element
     */
    public static function decorate(Element $element)
    {
        if (!$element->hasContext()) {
            $element->context(static::getDefaultContextForButton($element));
        }

        $element->addClass('btn-'.$element->getContext());

    }

    /**
     * Returns the default context for the button.
     *
     * @param Element $button
     * @return string
     */
    private static function getDefaultContextForButton(Element $button)
    {
        if (is_a($button,SubmitButtonElement::class)) {
            return 'primary';
        }

        if (is_a($button,ResetButtonElement::class)) {
            return 'secondary';
        }

        return 'default';
    }
}
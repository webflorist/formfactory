<?php

namespace Nicat\FormBuilder\Decorators\Bootstrap\v3;


use Nicat\FormBuilder\Elements\HelpTextElement;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;
use Nicat\HtmlBuilder\Elements\Abstracts\Element;


class StyleHelpTextElement extends Decorator
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
            HelpTextElement::class
        ];
    }

    /**
     * Decorates the element.
     *
     * @param Element $element
     */
    public static function decorate(Element $element)
    {
        $element->addClass('text-muted');
        $element->addClass('small');
    }
}
<?php

namespace Webflorist\FormFactory\Decorators\Bootstrap\v3;

use Webflorist\FormFactory\Components\FormControls\InputGroup;
use Webflorist\HtmlFactory\Decorators\Abstracts\Decorator;
use Webflorist\HtmlFactory\Elements\ButtonElement;
use Webflorist\HtmlFactory\Elements\SpanElement;

class StyleInputGroup extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var InputGroup
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
        return 'bootstrap:v3';
    }

    /**
     * Returns an array of class-names of elements, that should be decorated by this decorator.
     *
     * @return string[]
     */
    public static function getSupportedElements(): array
    {
        return [
            InputGroup::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        $this->element->addClass('input-group');

        foreach ($this->element->content->get() as $child) {

            // Strings get wrapped inside a Span with class 'input-group-addon'.
            if (is_string($child)) {
                $this->element->content->replaceChild(
                    $child,
                    (new SpanElement())->addClass('input-group-addon')->content($child)
                );
            }

            // Buttons get wrapped inside a Span with class 'input-group-btn'.
            if (is_object($child) && is_a($child, ButtonElement::class)) {
                $this->element->content->replaceChild(
                    $child,
                    (new SpanElement())->addClass('input-group-btn')->content($child)
                );
            }

        }

    }

}
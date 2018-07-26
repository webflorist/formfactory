<?php

namespace Nicat\FormFactory\Decorators\Bulma\v0;

use Nicat\FormFactory\Components\Additional\InputGroup;
use Nicat\HtmlFactory\Decorators\Abstracts\Decorator;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\DivElement;
use Nicat\HtmlFactory\Elements\SelectElement;

class StyleInputGroup extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var InputGroup
     */
    protected $element;

    /**
     * Returns an array of frontend-framework-ids, this decorator is specific for.
     *
     * @return string[]
     */
    public static function getSupportedFrameworks(): array
    {
        return [
            'bulma:0'
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
            InputGroup::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        $this->element->addClass('field has-addons');

        foreach ($this->element->content->get() as $child) {
            $this->wrapControl($child);
        }

    }
    /**
     * Bulma wants each field separately wrapped with a div with class 'control'.
     *
     * @param Element $element
     */
    private function wrapControl($element)
    {

        // In all other cases, we wrap the field itself with a div with the corresponding class(es).
        $this->element->content->replaceChild(
            $element,
            (new DivElement())->addClass($this->getControlClasses($element))->content($element)
        );
    }

    /**
     * For select-boxes we also add the 'select' class.
     *
     * @param Element $element
     * @return string
     */
    private function getControlClasses($element)
    {
        if ($element->is(SelectElement::class)) {
            return 'control select';
        }
        return 'control';
    }
}
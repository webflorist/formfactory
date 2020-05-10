<?php

namespace Webflorist\FormFactory\Decorators\Bootstrap\v4;

use Webflorist\FormFactory\Components\FormControls\InputGroup;
use Webflorist\HtmlFactory\Decorators\Abstracts\Decorator;
use Webflorist\HtmlFactory\Elements\DivElement;
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
        return 'bootstrap:v4';
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

        $prepend = [];
        $append = [];
        $doAppend = false;
        foreach ($this->element->content->get() as $child) {

            if ($child === $this->element->mainField) {
                $doAppend = true;
                continue;
            }

            if (is_string($child)) {
                $child = (new SpanElement())->addClass('input-group-text')->content($child);
            }

            if ($doAppend) {
                $append[] = $child;
            } else {
                $prepend[] = $child;
            }
        }

        $this->element->content->clear();

        if (count($prepend) > 0) {
            $this->element->appendContent((new DivElement())->addClass('input-group-prepend')->content($prepend));
        }

        $this->element->appendContent($this->element->mainField);

        if (count($append) > 0) {
            $this->element->appendContent((new DivElement())->addClass('input-group-append')->content($append));
        }

    }

}
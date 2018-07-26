<?php

namespace Nicat\FormFactory\Decorators\Bulma\v0;

use Nicat\FormFactory\Components\Additional\FieldLabel;
use Nicat\HtmlFactory\Decorators\Abstracts\Decorator;

class StyleFieldLabel extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var FieldLabel
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
            FieldLabel::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        $this->element->addClass('label');
    }
}
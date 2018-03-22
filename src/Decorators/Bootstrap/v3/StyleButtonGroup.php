<?php

namespace Nicat\FormFactory\Decorators\Bootstrap\v3;

use Nicat\FormFactory\Components\Additional\ButtonGroup;
use Nicat\HtmlFactory\Decorators\Abstracts\Decorator;

class StyleButtonGroup extends Decorator
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
            ButtonGroup::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        $this->element->addClass('btn-group');

    }
}
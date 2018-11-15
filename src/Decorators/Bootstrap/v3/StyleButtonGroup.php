<?php

namespace Webflorist\FormFactory\Decorators\Bootstrap\v3;

use Webflorist\FormFactory\Components\FormControls\ButtonGroup;
use Webflorist\HtmlFactory\Decorators\Abstracts\Decorator;

class StyleButtonGroup extends Decorator
{

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
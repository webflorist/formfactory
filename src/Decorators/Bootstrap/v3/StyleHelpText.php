<?php

namespace Nicat\FormBuilder\Decorators\Bootstrap\v3;

use Nicat\FormBuilder\Components\Additional\HelpText;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;

class StyleHelpText extends Decorator
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
            HelpText::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        $this->element->addClass('text-muted');
        $this->element->addClass('small');
    }
}
<?php

namespace Webflorist\FormFactory\Decorators\Bulma\v0;

use Webflorist\FormFactory\Components\FormControls\Button;
use Webflorist\FormFactory\Components\FormControls\ResetButton;
use Webflorist\FormFactory\Components\FormControls\SubmitButton;
use Webflorist\HtmlFactory\Decorators\Abstracts\Decorator;

class StyleButtons extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var Button|SubmitButton|ResetButton
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
            Button::class,
            SubmitButton::class,
            ResetButton::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        if (!$this->element->hasContext() && $this->element->is(SubmitButton::class)) {
            $this->element->context('primary');
        }

        if ($this->element->hasContext()) {
            $this->element->addClass('is-'.$this->element->getContext());
        }

    }
}
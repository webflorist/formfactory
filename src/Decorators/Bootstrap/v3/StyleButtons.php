<?php

namespace Nicat\FormFactory\Decorators\Bootstrap\v3;

use Nicat\FormFactory\Components\FormControls\Button;
use Nicat\FormFactory\Components\FormControls\ResetButton;
use Nicat\FormFactory\Components\FormControls\SubmitButton;
use Nicat\HtmlFactory\Decorators\Abstracts\Decorator;

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
        if (!$this->element->hasContext()) {
            $this->element->context($this->getDefaultContext());
        }

        $this->element->addClass('btn-'.$this->element->getContext());

    }

    /**
     * Returns the default context for the button.
     *
     * @return string
     */
    private function getDefaultContext()
    {
        if ($this->element->is(SubmitButton::class)) {
            return 'primary';
        }

        if ($this->element->is(ResetButton::class)) {
            return 'secondary';
        }

        return 'default';
    }
}
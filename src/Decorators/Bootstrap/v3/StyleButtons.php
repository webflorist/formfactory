<?php

namespace Nicat\FormBuilder\Decorators\Bootstrap\v3;

use Nicat\FormBuilder\Elements\ButtonElement;
use Nicat\FormBuilder\Elements\ResetButtonElement;
use Nicat\FormBuilder\Elements\SubmitButtonElement;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;

class StyleButtons extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var ButtonElement|SubmitButtonElement|ResetButtonElement
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
            ButtonElement::class,
            SubmitButtonElement::class,
            ResetButtonElement::class
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
        if ($this->element->is(SubmitButtonElement::class)) {
            return 'primary';
        }

        if ($this->element->is(ResetButtonElement::class)) {
            return 'secondary';
        }

        return 'default';
    }
}
<?php

namespace Nicat\FormFactory\Decorators\Bulma\v0;

use Nicat\FormFactory\Components\FormControls\Button;
use Nicat\FormFactory\Components\FormControls\ResetButton;
use Nicat\FormFactory\Components\FormControls\SubmitButton;
use Nicat\FormFactory\Utilities\ComponentLists;
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
     * Returns the group-ID of this decorator.
     *
     * Returning null means this decorator will always be applied.
     *
     * @return string|null
     */
    public static function getGroupId()
    {
        return 'bulma:v0';
    }

    /**
     * Returns an array of class-names of elements, that should be decorated by this decorator.
     *
     * @return string[]
     */
    public static function getSupportedElements(): array
    {
        return ComponentLists::buttons();
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
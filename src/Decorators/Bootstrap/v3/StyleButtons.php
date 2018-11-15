<?php

namespace Webflorist\FormFactory\Decorators\Bootstrap\v3;

use Webflorist\FormFactory\Components\FormControls\Button;
use Webflorist\FormFactory\Components\FormControls\ResetButton;
use Webflorist\FormFactory\Components\FormControls\SubmitButton;
use Webflorist\FormFactory\Utilities\ComponentLists;
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
        return ComponentLists::buttons();
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
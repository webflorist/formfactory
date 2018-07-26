<?php

namespace Nicat\FormFactory\Decorators\Bulma\v0;

use Nicat\FormFactory\Components\Additional\FieldLabel;
use Nicat\HtmlFactory\Components\CheckboxInputComponent;
use Nicat\HtmlFactory\Components\RadioInputComponent;
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
        $this->element->addClass($this->getLabelClass());
    }

    /**
     * Returns class to be used for $this->element.
     *
     * @return string
     */
    protected function getLabelClass(): string
    {

        // Checkboxes and Radio-buttons are directly wrapped inside the label,
        // which gets a special class.
        if (!is_null($this->element->field)) {

            if ($this->element->field->is(CheckboxInputComponent::class)) {
                return 'checkbox';
            }

            if ($this->element->field->is(RadioInputComponent::class)) {
                return 'radio';
            }

        }

        return 'label';
    }
}
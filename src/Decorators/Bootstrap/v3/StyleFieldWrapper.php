<?php

namespace Nicat\FormBuilder\Decorators\Bootstrap\v3;

use Nicat\FormBuilder\Components\FieldWrapper;
use Nicat\FormBuilder\Elements\RadioInputElement;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;
use Nicat\HtmlBuilder\Elements\CheckboxInputElement;

class StyleFieldWrapper extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var FieldWrapper
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
            FieldWrapper::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        $this->element->addClass($this->getFieldWrapperClass());
    }

    /**
     * Returns the correct class for the field's wrapper.
     *
     * @return string
     */
    private function getFieldWrapperClass()
    {
        if ($this->element->field->is(CheckboxInputElement::class)) {
            return 'checkbox';
        }

        if ($this->element->field->is(RadioInputElement::class)) {
            return 'radio';
        }

        return 'form-group';
    }
}
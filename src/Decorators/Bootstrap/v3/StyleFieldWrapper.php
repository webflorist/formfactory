<?php

namespace Nicat\FormFactory\Decorators\Bootstrap\v3;

use Nicat\FormFactory\Components\Additional\FieldWrapper;
use Nicat\FormFactory\Components\FormControls\CheckboxInput;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\HtmlFactory\Decorators\Abstracts\Decorator;

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
        if (!is_null($this->element->field)) {
            $this->element->addClass($this->getFieldWrapperClass());

            // Add error-class to wrapper, if field has errors.
            if ($this->element->field->hasErrors()) {
                $this->element->addClass('has-error');
            }
        }
    }

    /**
     * Returns the correct class for the field's wrapper.
     *
     * @return string
     */
    private function getFieldWrapperClass()
    {
        if ($this->element->field->is(CheckboxInput::class)) {
            return 'checkbox';
        }

        if ($this->element->field->is(RadioInput::class)) {
            return 'radio';
        }

        return 'form-group';
    }
}
<?php

namespace Nicat\FormFactory\Decorators\Bootstrap\v3;

use Nicat\FormFactory\Components\Helpers\FieldWrapper;
use Nicat\FormFactory\Components\FormControls\CheckboxInput;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\FormFactory\Utilities\Config\FormFactoryConfig;
use Nicat\FormFactory\Utilities\FormFactoryTools;
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
            FieldWrapper::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     * @throws \Nicat\HtmlFactory\Exceptions\VueDirectiveModifierNotAllowedException
     */
    public function decorate()
    {
        $this->element->addClass($this->getFieldWrapperClass());

        if (!is_null($this->element->field)) {
            $this->applyErrorClass();
        }
    }

    /**
     * Returns the correct class for the field's wrapper.
     *
     * @return string
     */
    protected function getFieldWrapperClass()
    {
        if (!is_null($this->element->field) && $this->element->field->is(CheckboxInput::class)) {
            return 'checkbox';
        }

        if (!is_null($this->element->field) && $this->element->field->is(RadioInput::class)) {
            return 'radio';
        }

        return 'form-group';
    }

    /**
     * Applies error-class, if field has errors.
     * If vue is used, this will be bound.
     *
     * @throws \Nicat\HtmlFactory\Exceptions\VueDirectiveModifierNotAllowedException
     */
    protected function applyErrorClass()
    {
        $field = $this->element->field;

        // We make this reactive, if vue is used.
        if ($this->element->field->isVueEnabled()) {
            $this->element->vBind('class',"{ 'has-error': fieldHasError('".$field->getFieldName()."') }");
            return;
        }

        // If vue is not used, we simply add the error-class to the wrapper, if the field has errors.
        if ($field->errors->hasErrors()) {
            $this->element->addClass('has-error');
        }
    }
}
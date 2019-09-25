<?php

namespace Webflorist\FormFactory\Decorators\Bootstrap\v4;

use Webflorist\FormFactory\Components\FormControls\Contracts\FieldInterface;
use Webflorist\FormFactory\Components\FormControls\Contracts\FormControlInterface;
use Webflorist\FormFactory\Components\FormControls\Traits\ErrorsTrait;
use Webflorist\FormFactory\Components\Helpers\FieldWrapper;
use Webflorist\FormFactory\Components\FormControls\CheckboxInput;
use Webflorist\FormFactory\Components\FormControls\RadioInput;
use Webflorist\HtmlFactory\Decorators\Abstracts\Decorator;
use Webflorist\HtmlFactory\Elements\Abstracts\Element;

class ApplyErrorClassToField extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var Element|FieldInterface|FormControlInterface|ErrorsTrait
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
        return 'bootstrap:v4';
    }

    /**
     * Returns an array of class-names of elements, that should be decorated by this decorator.
     *
     * @return string[]
     */
    public static function getSupportedElements(): array
    {
        return [
            ErrorsTrait::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     * @throws \Webflorist\HtmlFactory\Exceptions\VueDirectiveModifierNotAllowedException
     */
    public function decorate()
    {
        $this->applyErrorClass();
    }

    /**
     * Applies error-class, if field has errors.
     * If vue is used, this will be bound.
     *
     * @throws \Webflorist\HtmlFactory\Exceptions\VueDirectiveModifierNotAllowedException
     */
    protected function applyErrorClass()
    {

        // We make this reactive, if vue is used.
        if ($this->element->isVueEnabled()) {
            $this->element->vBind('class',"{ 'is-invalid': fieldHasError('".$this->element->getFieldName()."') }");
            return;
        }

        // If vue is not used, we simply add the error-class, if the field has errors.
        if ($this->element->errors->hasErrors()) {
            $this->element->addClass('is-invalid');
        }
    }

}

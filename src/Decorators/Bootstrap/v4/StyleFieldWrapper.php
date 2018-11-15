<?php

namespace Webflorist\FormFactory\Decorators\Bootstrap\v4;

use Webflorist\FormFactory\Components\Helpers\FieldWrapper;
use Webflorist\FormFactory\Components\FormControls\CheckboxInput;
use Webflorist\FormFactory\Components\FormControls\RadioInput;
use Webflorist\FormFactory\Decorators\Bootstrap\v3\StyleFieldWrapper as Bootstrap3StyleFieldWrapper;

class StyleFieldWrapper extends Bootstrap3StyleFieldWrapper
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
            FieldWrapper::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     * @throws \Webflorist\HtmlFactory\Exceptions\VueDirectiveModifierNotAllowedException
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
        $class = 'form-group';

        // FieldWrappers for RadioInputs, that belong to a RadioGroup do not get the form-group class.
        if (isset($this->element->field->belongsToGroup) && $this->element->field->belongsToGroup === true) {
            $class = '';
        }

        if (!is_null($this->element->field) && ($this->element->field->is(CheckboxInput::class) || $this->element->field->is(RadioInput::class))) {
            $class .= ' form-check';

            if ($this->element->field->isInline()) {
                $class .= ' form-check-inline';
            }

        }

        return $class;
    }

}
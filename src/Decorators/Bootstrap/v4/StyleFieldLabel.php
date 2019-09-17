<?php

namespace Webflorist\FormFactory\Decorators\Bootstrap\v4;

use Webflorist\FormFactory\Components\Helpers\FieldLabel;
use Webflorist\FormFactory\Components\Helpers\FieldWrapper;
use Webflorist\FormFactory\Components\FormControls\CheckboxInput;
use Webflorist\FormFactory\Components\FormControls\RadioInput;
use Webflorist\FormFactory\Decorators\Bootstrap\v3\StyleFieldWrapper as Bootstrap3StyleFieldWrapper;

class StyleFieldLabel extends Bootstrap3StyleFieldWrapper
{

    /**
     * The element to be decorated.
     *
     * @var FieldLabel
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
            FieldLabel::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        if ($this->element->field->is(RadioInput::class) || $this->element->field->is(CheckboxInput::class)) {
            $this->element->addClass('custom-control-label');
        }
        elseif ($this->element->srOnly) {
            $this->element->addClass('sr-only');
        }
    }


}

<?php

namespace Nicat\FormFactory\Decorators\Bootstrap\v3;

use Nicat\FormFactory\Components\Helpers\FieldLabel;
use Nicat\FormFactory\Components\Helpers\FieldWrapper;
use Nicat\FormFactory\Components\FormControls\CheckboxInput;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\FormFactory\Decorators\Bootstrap\v3\StyleFieldWrapper as Bootstrap3StyleFieldWrapper;

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
            FieldLabel::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        // In Bootstrap 3, checkable fields are wrapped in their label-tag.
        $this->element->wrapCheckable = true;
    }


}
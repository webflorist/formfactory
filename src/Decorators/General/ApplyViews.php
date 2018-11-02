<?php

namespace Nicat\FormFactory\Decorators\General;

use Nicat\FormFactory\Components\Additional\FieldWrapper;
use Nicat\FormFactory\Components\Additional\RadioGroup;
use Nicat\FormFactory\Components\Form;
use Nicat\FormFactory\Utilities\ComponentLists;
use Nicat\HtmlFactory\Decorators\Abstracts\Decorator;
use Nicat\HtmlFactory\Elements\Abstracts\Element;

/**
 * Apply various decorations to FormFactory-fields.
 *
 * Class DecorateFields
 * @package Nicat\FormFactory\Decorators\General
 */
class ApplyViews extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var Element
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
        return null;
    }

    /**
     * Returns an array of class-names of elements, that should be decorated by this decorator.
     *
     * @return string[]
     */
    public static function getSupportedElements(): array
    {
        return array_merge(
            ComponentLists::fields(),
            [
                Form::class,
                RadioGroup::class
            ]
        );
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        if (config('formfactory.views.enabled') && is_null($this->element->view)) {
            try {
                $this->element->view(config('formfactory.views.base') . '.' . kebab_case((new \ReflectionClass($this->element))->getShortName()));
            } catch (\ReflectionException $e) {
            }
        }

    }
}
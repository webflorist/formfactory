<?php

namespace Nicat\FormFactory\Decorators\Vue;

use Nicat\FormFactory\Components\Additional\RequiredFieldIndicator;
use Nicat\FormFactory\Utilities\FormFactoryTools;
use Nicat\HtmlFactory\Decorators\Abstracts\Decorator;
use Nicat\HtmlFactory\Elements\Abstracts\Element;

/**
 * Apply various decorations to FormFactory-fields.
 *
 * Class DecorateFields
 * @package Nicat\FormFactory\Decorators\General
 */
class DecorateRequiredFieldIndicator extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var Element|RequiredFieldIndicator
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
        return [
            RequiredFieldIndicator::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        if (!is_null($this->element->field)) {
            $this->element->vIf('fields.'.FormFactoryTools::convertArrayFieldHtmlName2JsNotation($this->element->field->attributes->name).'.isRequired');
        }

    }

}
<?php

namespace Nicat\FormBuilder\Decorators\General;

use Nicat\FormBuilder\Elements\OptionElement;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;

/**
 * Automatically generates the option-text for option-elements without a manually set content.
 * Uses auto-translation.
 *
 * Class AutoGenerateOptionTexts
 * @package Nicat\FormBuilder\Decorators\General
 */
class AutoGenerateOptionTexts extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var OptionElement
     */
    protected $element;

    /**
     * Returns an array of frontend-framework-ids, this decorator is specific for.
     * Returning an empty array means all frameworks are supported.
     *
     * @return string[]
     */
    public static function getSupportedFrameworks(): array
    {
        return [];
    }

    /**
     * Returns an array of class-names of elements, that should be decorated by this decorator.
     *
     * @return string[]
     */
    public static function getSupportedElements(): array
    {
        return [
            OptionElement::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        /** @var OptionElement $this->element */
        if (!$this->element->hasChildren()) {
            $this->element->content(
                $this->element->performAutoTranslation(null,'',$this->element->attributes->getValue('value'))
            );
        }

    }
}
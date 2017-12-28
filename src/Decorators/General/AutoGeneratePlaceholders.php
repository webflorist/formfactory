<?php

namespace Nicat\FormBuilder\Decorators\General;

use Nicat\FormBuilder\Elements\EmailInputElement;
use Nicat\FormBuilder\Elements\TextareaElement;
use Nicat\FormBuilder\Elements\TextInputElement;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;
use Nicat\HtmlBuilder\Elements\Abstracts\Element;
use Nicat\HtmlBuilder\Elements\Traits\AllowsPlaceholderAttribute;

/**
 * Automatically generates the placeholder-text for fields without a manually set placeholder.
 * Uses auto-translation.
 *
 * Class AutoGeneratePlaceholders
 * @package Nicat\FormBuilder\Decorators\General
 */
class AutoGeneratePlaceholders extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var Element|AllowsPlaceholderAttribute
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
            TextInputElement::class,
            EmailInputElement::class,
            TextareaElement::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        if (!$this->element->attributes->isSet('placeholder')) {
            $this->element->placeholder(
                ucwords($this->element->attributes->getValue('name'))
            );
        }
    }
}
<?php

namespace Nicat\FormBuilder\Decorators\General;

use Nicat\FormBuilder\Elements\ButtonElement;
use Nicat\FormBuilder\Elements\OptionElement;
use Nicat\FormBuilder\Elements\ResetButtonElement;
use Nicat\FormBuilder\Elements\SubmitButtonElement;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;

/**
 * Automatically generates the button-text for button-elements without a manually set content.
 * Uses auto-translation.
 *
 * Class AutoGenerateButtonTexts
 * @package Nicat\FormBuilder\Decorators\General
 */
class AutoGenerateButtonTexts extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var ButtonElement
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
            ButtonElement::class,
            SubmitButtonElement::class,
            ResetButtonElement::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        if (!$this->element->hasChildren()) {
            $this->element->content(
                $this->element->performAutoTranslation(null,'',ucwords($this->element->attributes->getValue('name')))
            );
        }

    }
}
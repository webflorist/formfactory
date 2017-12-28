<?php

namespace Nicat\FormBuilder\Decorators\General;

use Nicat\FormBuilder\Elements\CheckboxInputElement;
use Nicat\FormBuilder\Elements\ColorInputElement;
use Nicat\FormBuilder\Elements\DateInputElement;
use Nicat\FormBuilder\Elements\DatetimeInputElement;
use Nicat\FormBuilder\Elements\DatetimeLocalInputElement;
use Nicat\FormBuilder\Elements\EmailInputElement;
use Nicat\FormBuilder\Elements\FileInputElement;
use Nicat\FormBuilder\Elements\NumberInputElement;
use Nicat\FormBuilder\Elements\RadioInputElement;
use Nicat\FormBuilder\Elements\TextareaElement;
use Nicat\FormBuilder\Elements\TextInputElement;
use Nicat\FormBuilder\Elements\Traits\CanHaveHelpText;
use Nicat\FormBuilder\Elements\Traits\UsesAutoTranslation;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;

/**
 * Automatically generates help-texts for fields without a manually set help-text.
 * Uses auto-translation.
 *
 * Class AutoGenerateHelpTexts
 * @package Nicat\FormBuilder\Decorators\General
 */
class AutoGenerateHelpTexts extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var CanHaveHelpText|UsesAutoTranslation
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
            NumberInputElement::class,
            ColorInputElement::class,
            DateInputElement::class,
            DatetimeInputElement::class,
            DatetimeLocalInputElement::class,
            EmailInputElement::class,
            FileInputElement::class,
            CheckboxInputElement::class,
            RadioInputElement::class,
            TextareaElement::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        if (!$this->element->hasHelpText()) {
            $helpText = $this->element->performAutoTranslation(null, 'HelpText');
            if ($helpText !== null) {
                $this->element->helpText($helpText);
            }
        }
    }
}
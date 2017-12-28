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
use Nicat\FormBuilder\Elements\SelectElement;
use Nicat\FormBuilder\Elements\TextareaElement;
use Nicat\FormBuilder\Elements\TextInputElement;
use Nicat\FormBuilder\Elements\Traits\CanHaveLabel;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;
use Nicat\HtmlBuilder\Elements\Abstracts\Element;

/**
 * Adds an indication to the label of required form fields.
 *
 * Class IndicateRequiredFields
 * @package Nicat\FormBuilder\Decorators\General
 */
class IndicateRequiredFields extends Decorator
{
    /**
     * The element to be decorated.
     *
     * @var Element|CanHaveLabel
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
            TextareaElement::class,
            SelectElement::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        if (!$this->element->is(RadioInputElement::class)) {
            if (!is_null($this->element->label) && $this->element->attributes->isSet('required')) {
                $this->element->label(
                    $this->element->label . '<sup>*</sup>'
                );
            }
        }
    }
}
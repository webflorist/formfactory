<?php

namespace Nicat\FormBuilder\Decorators\General;

use Nicat\FormBuilder\Elements\ButtonElement;
use Nicat\FormBuilder\Elements\CheckboxInputElement;
use Nicat\FormBuilder\Elements\ColorInputElement;
use Nicat\FormBuilder\Elements\DateInputElement;
use Nicat\FormBuilder\Elements\DatetimeInputElement;
use Nicat\FormBuilder\Elements\DatetimeLocalInputElement;
use Nicat\FormBuilder\Elements\EmailInputElement;
use Nicat\FormBuilder\Elements\FileInputElement;
use Nicat\FormBuilder\Elements\HiddenInputElement;
use Nicat\FormBuilder\Elements\NumberInputElement;
use Nicat\FormBuilder\Elements\OptionElement;
use Nicat\FormBuilder\Elements\RadioInputElement;
use Nicat\FormBuilder\Elements\ResetButtonElement;
use Nicat\FormBuilder\Elements\SelectElement;
use Nicat\FormBuilder\Elements\SubmitButtonElement;
use Nicat\FormBuilder\Elements\TextareaElement;
use Nicat\FormBuilder\Elements\TextInputElement;
use Nicat\FormBuilder\FormBuilder;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;

/**
 * Automatically generates an id for fields without a manually set id.
 * The generated id will be:
 *
 * Class AutoGenerateFieldIDs
 * @package Nicat\FormBuilder\Decorators\General
 */
class AutoGenerateFieldIDs extends Decorator
{

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
            HiddenInputElement::class,
            CheckboxInputElement::class,
            FileInputElement::class,
            RadioInputElement::class,
            TextareaElement::class,
            SelectElement::class,
            OptionElement::class,
            ButtonElement::class,
            ResetButtonElement::class,
            SubmitButtonElement::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        if (!$this->element->attributes->isSet('id')) {

            /** @var FormBuilder $formBuilderService */
            $formBuilderService = app()[FormBuilder::class];

            // Auto-generated IDs always start with formID.
            $fieldId = $formBuilderService->openForm->attributes->getValue('id');

            // The field-element containing the field-name is always $this->element,
            // except with option-elements, where we use the currently open select-element.
            $fieldElement = $this->element;
            if ($this->element->is(OptionElement::class)) {
                $fieldElement = $formBuilderService->openSelect;
            }

            // Append the field-name.
            $fieldId .= '_' . $fieldElement->attributes->getValue('name');

            // For radio-buttons and options we also append the value.
            if ($this->element->is(RadioInputElement::class) || $this->element->is(OptionElement::class)) {
                $fieldId .= '_' . $this->element->attributes->getValue('value');
            }

            $this->element->id($fieldId);
        }
    }
}
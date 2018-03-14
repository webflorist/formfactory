<?php

namespace Nicat\FormBuilder\Utilities\FieldValues;

use Nicat\FormBuilder\Components\Form;
use Nicat\FormBuilder\FormBuilder;
use Nicat\HtmlBuilder\Elements\Abstracts\Element;

/**
 * Applies default-values, that were set via the 'values' method on the Form (if the form was not submitted during last request).
 * Applies submitted values (if the form was submitted during last request).
 *
 * Class FieldValueProcessor
 * @package Nicat\FormBuilder
 */
class FieldValueProcessor
{

    /**
     * Apply values to $element.
     *
     * @param FieldValueProcessorInterface|Element $element
     */
    public static function process(FieldValueProcessorInterface $element)
    {

        /** @var Form $openForm */
        $openForm = app(FormBuilder::class)->openForm;

        $fieldName = $element->attributes->name;

        if (!$openForm->wasSubmitted && $openForm->values->fieldHasDefaultValue($fieldName)) {
            $element->applyFieldValue($openForm->values->getDefaultValueForField($fieldName));
        }

        if ($openForm->wasSubmitted && $openForm->values->fieldHasSubmittedValue($fieldName)) {
            $element->applyFieldValue($openForm->values->getSubmittedValueForField($fieldName));
        }
    }

}
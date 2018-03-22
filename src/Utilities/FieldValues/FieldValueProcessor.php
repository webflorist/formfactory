<?php

namespace Nicat\FormFactory\Utilities\FieldValues;

use Nicat\FormFactory\Components\Form;
use Nicat\FormFactory\FormFactory;
use Nicat\HtmlFactory\Elements\Abstracts\Element;

/**
 * Applies default-values, that were set via the 'values' method on the Form (if the form was not submitted during last request).
 * Applies submitted values (if the form was submitted during last request).
 *
 * Class FieldValueProcessor
 * @package Nicat\FormFactory
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
        $openForm = app(FormFactory::class)->getOpenForm();

        $fieldName = $element->attributes->name;

        if (!$openForm->wasSubmitted && $openForm->values->fieldHasDefaultValue($fieldName)) {
            $element->applyFieldValue($openForm->values->getDefaultValueForField($fieldName));
        }

        if ($openForm->wasSubmitted && $openForm->values->fieldHasSubmittedValue($fieldName)) {
            $element->applyFieldValue($openForm->values->getSubmittedValueForField($fieldName));
        }
    }

}
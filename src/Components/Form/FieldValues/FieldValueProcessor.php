<?php

namespace Nicat\FormFactory\Components\Form\FieldValues;

use Nicat\FormFactory\Components\FormControls\Contracts\FieldInterface;
use Nicat\FormFactory\Components\FormControls\Contracts\FormControlInterface;
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
     * Apply values to $field.
     *
     * @param FieldInterface|FormControlInterface|Element $field
     */
    public static function process(FieldInterface $field)
    {

        $form = $field->getForm();
        $fieldName = $field->getFieldName();

        // Submitted values always take precedence.
        if ($form->wasSubmitted && $form->values->fieldHasSubmittedValue($fieldName)) {
            $field->applyFieldValue($form->values->getSubmittedValueForField($fieldName));
            return;
        }

        // If the field already has a value set (e.g. set via the value()-method),
        // we leave it at that.
        if ($field->fieldHasValue()) {
            return;
        }

        // If the form was not submitted, but a value for the this field was stated via
        // the values()-method of the Form::open() call, we apply this default value.
        if (!$form->wasSubmitted && $form->values->fieldHasDefaultValue($fieldName)) {
            $field->applyFieldValue($form->values->getDefaultValueForField($fieldName));
        }
    }

}
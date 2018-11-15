<?php

namespace Webflorist\FormFactory\Utilities\FieldValues;

use Webflorist\FormFactory\Components\Form;
use Webflorist\FormFactory\Components\FormControls\FileInput;
use Webflorist\FormFactory\FormFactory;
use Webflorist\HtmlFactory\Elements\Abstracts\Element;

/**
 * Applies default-values, that were set via the 'values' method on the Form (if the form was not submitted during last request).
 * Applies submitted values (if the form was submitted during last request).
 *
 * Class FieldValueProcessor
 * @package Webflorist\FormFactory
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

        // Submitted values always take precedence.
        if ($openForm->wasSubmitted && $openForm->values->fieldHasSubmittedValue($fieldName)) {
            $element->applyFieldValue($openForm->values->getSubmittedValueForField($fieldName));
            return;
        }

        // If the field already has a value set (e.g. set via the value()-method),
        // we leave it at that.
        if ($element->fieldHasValue()) {
            return;
        }

        // If the form was not submitted, but a value for the this field was stated via
        // the values()-method of the Form::open() call, we apply this default value.
        if (!$openForm->wasSubmitted && $openForm->values->fieldHasDefaultValue($fieldName)) {
            $element->applyFieldValue($openForm->values->getDefaultValueForField($fieldName));
        }
    }

}
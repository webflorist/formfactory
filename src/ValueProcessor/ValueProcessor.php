<?php

namespace Nicat\FormBuilder\ValueProcessor;

use Nicat\FormBuilder\Elements\FormElement;
use Nicat\FormBuilder\FormBuilder;
use Nicat\HtmlBuilder\Elements\Abstracts\Element;

/**
 * Applies default-values, that were set via the 'values' method on the FormElement (if the form was not submitted during last request).
 * Applies submitted values (if the form was submitted during last request).
 *
 * Class ValueProcessor
 * @package Nicat\FormBuilder
 */
class ValueProcessor
{

    /**
     * @var Element|ValueProcessorInterface
     */
    private $element;

    /**
     * ValueProcessor constructor.
     *
     * @param ValueProcessorInterface $element
     */
    public function __construct(ValueProcessorInterface $element)
    {
        $this->element = $element;

        /** @var FormElement $openForm */
        $openForm = app(FormBuilder::class)->openForm;

        $fieldName = $this->element->attributes->name;

        if (!$openForm->wasSubmitted && $openForm->fieldHasDefaultValue($fieldName)) {
            $element->applyFieldValue($openForm->getDefaultValueForField($fieldName));
        }

        if ($openForm->wasSubmitted && $openForm->fieldHasSubmittedValue($fieldName)) {
            $element->applyFieldValue($openForm->getSubmittedValueForField($fieldName));
        }
    }


}
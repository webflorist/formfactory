<?php

namespace Nicat\FormBuilder\FieldValues;

use Nicat\FormBuilder\Elements\FormElement;
use Nicat\FormBuilder\FormBuilder;
use Nicat\FormBuilder\FormBuilderTools;
use Nicat\HtmlBuilder\Elements\Abstracts\Element;

/**
 * Manages field-values for forms.
 *
 * Class FieldValueProcessor
 * @package Nicat\FormBuilder
 */
class FieldValueManager
{

    /**
     * The FormElement this ValueManager belongs to.
     *
     * @var FormElement
     */
    private $formElement;

    /**
     * Array of default-values for fields.
     *
     * @var array
     */
    private $defaultValues = [];

    /**
     * ValueManager constructor.
     *
     * @param FormElement $formElement
     */
    public function __construct(FormElement $formElement)
    {
        $this->formElement = $formElement;
    }

    /**
     * Set default-values to be used for all fields.
     *
     * @param array $values
     */
    public function setDefaultValues($values)
    {
        $this->defaultValues = $values;
    }

    /**
     * Gets the default-value of a field stored in $this->defaultValues.
     *
     * @param string $fieldName
     * @return string|null
     */
    public function getDefaultValueForField(string $fieldName)
    {
        $fieldName = FormBuilderTools::convertArrayFieldHtmlName2DotNotation($fieldName);
        if (array_has($this->defaultValues, $fieldName)) {
            return (array_get($this->defaultValues, $fieldName));
        }
        return null;
    }

    /**
     * Checks, if a default-value of a field was stored in $this->defaultValues.
     *
     * @param string $fieldName
     * @return bool
     */
    public function fieldHasDefaultValue(string $fieldName)
    {
        $fieldName = FormBuilderTools::convertArrayFieldHtmlName2DotNotation($fieldName);
        return array_has($this->defaultValues, $fieldName);
    }

    /**
     * Gets the submitted value of a field for the current form
     *
     * @param string $fieldName
     * @return mixed
     */
    public function getSubmittedValueForField(string $fieldName)
    {
        if ($this->formElement->wasSubmitted) {
            $fieldName = FormBuilderTools::convertArrayFieldHtmlName2DotNotation($fieldName);
            return request()->old($fieldName);
        }
        return null;
    }

    /**
     * Checks, if a field was submitted for the current form
     *
     * @param string $fieldName
     * @return bool
     */
    public function fieldHasSubmittedValue(string $fieldName) : bool
    {
        $fieldName = FormBuilderTools::convertArrayFieldHtmlName2DotNotation($fieldName);
        return $this->formElement->wasSubmitted && !is_null(request()->old($fieldName));
    }

}
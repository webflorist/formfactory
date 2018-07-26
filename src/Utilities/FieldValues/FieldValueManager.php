<?php

namespace Nicat\FormFactory\Utilities\FieldValues;

use Nicat\FormFactory\Components\Form;
use Nicat\FormFactory\Utilities\FormFactoryTools;

/**
 * Manages field-values for forms.
 *
 * Class FieldValueProcessor
 * @package Nicat\FormFactory
 */
class FieldValueManager
{

    /**
     * The Form this ValueManager belongs to.
     *
     * @var Form
     */
    private $form;

    /**
     * Array of default-values for fields.
     *
     * @var array
     */
    private $defaultValues = [];

    /**
     * ValueManager constructor.
     *
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
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
        $fieldName = FormFactoryTools::convertArrayFieldHtmlName2DotNotation($fieldName);
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
        $fieldName = FormFactoryTools::convertArrayFieldHtmlName2DotNotation($fieldName);

        if (!array_has($this->defaultValues, $fieldName)) {
            return false;
        }

        // An empty array is not considered as a default-value.
        $value = array_get($this->defaultValues, $fieldName);
        if (is_array($value) && (count($value)===0)) {
            return false;
        }

        return true;
    }

    /**
     * Gets the submitted value of a field for the current form
     *
     * @param string $fieldName
     * @return mixed
     */
    public function getSubmittedValueForField(string $fieldName)
    {
        if ($this->form->wasSubmitted) {
            $fieldName = FormFactoryTools::convertArrayFieldHtmlName2DotNotation($fieldName);
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
        $fieldName = FormFactoryTools::convertArrayFieldHtmlName2DotNotation($fieldName);
        return $this->form->wasSubmitted && !is_null(request()->old($fieldName));
    }

}
<?php

namespace Webflorist\FormFactory\Utilities\FieldValues;

interface FieldValueProcessorInterface
{

    /**
     * Apply a value to a field.
     *
     * @param $value
     */
    public function applyFieldValue($value);

    /**
     * Does this field currently have a value set?
     *
     * @return bool
     */
    public function fieldHasValue();

}
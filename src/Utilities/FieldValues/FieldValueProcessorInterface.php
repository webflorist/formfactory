<?php

namespace Nicat\FormBuilder\Utilities\FieldValues;

interface FieldValueProcessorInterface
{

    /**
     * Apply a value to a field.
     *
     * @param $value
     */
    public function applyFieldValue($value);

}
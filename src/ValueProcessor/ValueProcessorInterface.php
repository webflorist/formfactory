<?php

namespace Nicat\FormBuilder\ValueProcessor;

interface ValueProcessorInterface
{

    /**
     * Apply a value to a field.
     *
     * @param $value
     */
    public function applyFieldValue($value);

}
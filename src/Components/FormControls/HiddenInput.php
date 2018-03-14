<?php

namespace Nicat\FormBuilder\Components\FormControls;

use Nicat\FormBuilder\Utilities\FieldValues\FieldValueProcessorInterface;
use Nicat\FormBuilder\Components\Traits\CanHaveErrors;
use Nicat\HtmlBuilder\Components\HiddenInputComponent;

class HiddenInput extends HiddenInputComponent implements FieldValueProcessorInterface
{
    use CanHaveErrors;

    /**
     * Apply a value to a field.
     *
     * @param $value
     */
    public function applyFieldValue($value)
    {
        $this->value($value);
    }
}
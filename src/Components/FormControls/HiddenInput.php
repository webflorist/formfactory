<?php

namespace Nicat\FormFactory\Components\FormControls;

use Nicat\FormFactory\Utilities\FieldValues\FieldValueProcessorInterface;
use Nicat\FormFactory\Components\Traits\CanHaveErrors;
use Nicat\HtmlFactory\Components\HiddenInputComponent;

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
<?php

namespace Nicat\FormBuilder\Elements;

use Nicat\FormBuilder\FieldValues\FieldValueProcessorInterface;
use Nicat\FormBuilder\Elements\Traits\CanHaveErrors;

class HiddenInputElement extends \Nicat\HtmlBuilder\Elements\HiddenInputElement implements FieldValueProcessorInterface
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
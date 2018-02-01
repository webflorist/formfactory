<?php

namespace Nicat\FormBuilder\Elements;

use Nicat\FormBuilder\ValueProcessor\ValueProcessorInterface;
use Nicat\FormBuilder\Elements\Traits\CanHaveErrors;

class HiddenInputElement extends \Nicat\HtmlBuilder\Elements\HiddenInputElement implements ValueProcessorInterface
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
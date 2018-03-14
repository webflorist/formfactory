<?php

namespace Nicat\FormBuilder\Components\FormControls;

use Nicat\FormBuilder\Utilities\AutoTranslation\AutoTranslationInterface;
use Nicat\FormBuilder\Utilities\FieldValues\FieldValueProcessorInterface;
use Nicat\FormBuilder\Components\Traits\CanAutoSubmit;
use Nicat\FormBuilder\Components\Traits\CanHaveErrors;
use Nicat\FormBuilder\Components\Traits\CanHaveHelpText;
use Nicat\FormBuilder\Components\Traits\CanHaveLabel;
use Nicat\FormBuilder\Components\Traits\CanHaveRules;
use Nicat\FormBuilder\Components\Traits\CanPerformAjaxValidation;
use Nicat\FormBuilder\Components\Traits\UsesAutoTranslation;
use Nicat\HtmlBuilder\Components\DatetimeLocalInputComponent;

class DatetimeLocalInput extends DatetimeLocalInputComponent implements FieldValueProcessorInterface, AutoTranslationInterface
{
    use CanHaveLabel,
        CanHaveRules,
        CanHaveHelpText,
        UsesAutoTranslation,
        CanHaveErrors,
        CanAutoSubmit,
        CanPerformAjaxValidation;

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
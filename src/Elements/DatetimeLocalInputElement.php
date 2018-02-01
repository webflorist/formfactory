<?php

namespace Nicat\FormBuilder\Elements;

use Nicat\FormBuilder\AutoTranslation\AutoTranslationInterface;
use Nicat\FormBuilder\ValueProcessor\ValueProcessorInterface;
use Nicat\FormBuilder\Elements\Traits\CanAutoSubmit;
use Nicat\FormBuilder\Elements\Traits\CanHaveErrors;
use Nicat\FormBuilder\Elements\Traits\CanHaveHelpText;
use Nicat\FormBuilder\Elements\Traits\CanHaveLabel;
use Nicat\FormBuilder\Elements\Traits\CanHaveRules;
use Nicat\FormBuilder\Elements\Traits\CanPerformAjaxValidation;
use Nicat\FormBuilder\Elements\Traits\UsesAutoTranslation;

class DatetimeLocalInputElement extends \Nicat\HtmlBuilder\Elements\DatetimeLocalInputElement implements ValueProcessorInterface, AutoTranslationInterface
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
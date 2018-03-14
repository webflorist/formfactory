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
use Nicat\HtmlBuilder\Components\RadioInputComponent;

class RadioInput extends RadioInputComponent implements FieldValueProcessorInterface, AutoTranslationInterface
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
        $this->checked($value === $this->attributes->value);
    }

    /**
     * Returns the base translation-key for auto-translations for this object.
     *
     * @return string
     */
    function getAutoTranslationKey(): string
    {
        return $this->attributes->name . '_' . $this->attributes->value;
    }
}
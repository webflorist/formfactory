<?php

namespace Nicat\FormBuilder\Elements;

use Nicat\FormBuilder\AutoTranslation\AutoTranslationInterface;
use Nicat\FormBuilder\FieldValues\FieldValueProcessorInterface;
use Nicat\FormBuilder\Elements\Traits\CanAutoSubmit;
use Nicat\FormBuilder\Elements\Traits\CanHaveErrors;
use Nicat\FormBuilder\Elements\Traits\CanHaveHelpText;
use Nicat\FormBuilder\Elements\Traits\CanHaveLabel;
use Nicat\FormBuilder\Elements\Traits\CanHaveRules;
use Nicat\FormBuilder\Elements\Traits\CanPerformAjaxValidation;
use Nicat\FormBuilder\Elements\Traits\UsesAutoTranslation;

class RadioInputElement extends \Nicat\HtmlBuilder\Elements\RadioInputElement implements FieldValueProcessorInterface, AutoTranslationInterface
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
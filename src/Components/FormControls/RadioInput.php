<?php

namespace Nicat\FormFactory\Components\FormControls;

use Nicat\FormFactory\Components\HelpText\HelpTextInterface;
use Nicat\FormFactory\Utilities\AutoTranslation\AutoTranslationInterface;
use Nicat\FormFactory\Utilities\FieldValues\FieldValueProcessorInterface;
use Nicat\FormFactory\Components\Traits\CanAutoSubmit;
use Nicat\FormFactory\Components\Traits\CanHaveErrors;
use Nicat\FormFactory\Components\Traits\CanHaveHelpText;
use Nicat\FormFactory\Components\Traits\CanHaveLabel;
use Nicat\FormFactory\Components\Traits\CanHaveRules;
use Nicat\FormFactory\Components\Traits\CanPerformAjaxValidation;
use Nicat\FormFactory\Components\Traits\UsesAutoTranslation;
use Nicat\HtmlFactory\Components\RadioInputComponent;

class RadioInput extends RadioInputComponent implements FieldValueProcessorInterface, AutoTranslationInterface, HelpTextInterface
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
        if (is_bool($value)) {
            $value = (int)$value;
        }
        $this->checked((string)$value === $this->attributes->value);
    }

    /**
     * Returns the base translation-key for auto-translations for this object.
     *
     * @return string
     */
    public function getAutoTranslationKey(): string
    {
        return $this->attributes->name . '_' . $this->attributes->value;
    }

    /**
     * Does this field currently have a value set?
     *
     * @return bool
     */
    public function fieldHasValue()
    {
        return $this->attributes->isSet('checked');
    }
}
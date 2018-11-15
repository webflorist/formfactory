<?php

namespace Webflorist\FormFactory\Components\FormControls;

use Webflorist\FormFactory\Components\HelpText\HelpTextInterface;
use Webflorist\FormFactory\Utilities\AutoTranslation\AutoTranslationInterface;
use Webflorist\FormFactory\Utilities\FieldValues\FieldValueProcessorInterface;
use Webflorist\FormFactory\Components\Traits\CanAutoSubmit;
use Webflorist\FormFactory\Components\Traits\CanHaveErrors;
use Webflorist\FormFactory\Components\Traits\CanHaveHelpText;
use Webflorist\FormFactory\Components\Traits\CanHaveLabel;
use Webflorist\FormFactory\Components\Traits\CanHaveRules;
use Webflorist\FormFactory\Components\Traits\CanPerformAjaxValidation;
use Webflorist\FormFactory\Components\Traits\UsesAutoTranslation;
use Webflorist\HtmlFactory\Components\RadioInputComponent;

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
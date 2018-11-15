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
use Webflorist\HtmlFactory\Components\UrlInputComponent;

class UrlInput extends UrlInputComponent implements FieldValueProcessorInterface, AutoTranslationInterface, HelpTextInterface
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

    /**
     * Does this field currently have a value set?
     *
     * @return bool
     */
    public function fieldHasValue()
    {
        return $this->attributes->isSet('value');
    }

}
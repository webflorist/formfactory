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
use Webflorist\HtmlFactory\Elements\SelectElement;

class Select extends SelectElement implements FieldValueProcessorInterface, AutoTranslationInterface, HelpTextInterface
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
        // $value may be a string (in case of a non-multiple-select) or an array (in case of a multiple-select).
        // We make sure, $value is an array in any case.
        if (!is_array($value)) {
            $value = [$value];
        }

        // Format each option.
        foreach ($this->content->getChildrenByClassName(Option::class) as $optionKey => $option) {

            /** @var Option $option */
            $option->selected(
                in_array($option->attributes->value, $value)
            );

        }
    }

    /**
     * Does this field currently have a value set?
     *
     * @return bool
     */
    public function fieldHasValue()
    {
        foreach ($this->content->getChildrenByClassName(Option::class) as $optionKey => $option) {

            /** @var Option $option */
            if ($option->attributes->isSet('selected')) {
                return true;
            }

        }

        return false;
    }
}
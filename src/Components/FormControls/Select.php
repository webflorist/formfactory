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
use Nicat\HtmlBuilder\Elements\SelectElement;

class Select extends SelectElement implements FieldValueProcessorInterface, AutoTranslationInterface
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
        foreach ($this->getChildrenByClassName(Option::class) as $optionKey => $option) {

            /** @var Option $option */
            $option->selected(
                in_array($option->attributes->value, $value)
            );

        }
    }
}
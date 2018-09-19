<?php

namespace Nicat\FormFactory\Components\FormControls;

use Nicat\FormFactory\Components\HelpText\HelpTextInterface;
use Nicat\FormFactory\FormFactory;
use Nicat\FormFactory\Utilities\AutoTranslation\AutoTranslationInterface;
use Nicat\FormFactory\Utilities\FieldValues\FieldValueProcessorInterface;
use Nicat\FormFactory\Components\Traits\CanAutoSubmit;
use Nicat\FormFactory\Components\Traits\CanHaveErrors;
use Nicat\FormFactory\Components\Traits\CanHaveHelpText;
use Nicat\FormFactory\Components\Traits\CanHaveLabel;
use Nicat\FormFactory\Components\Traits\CanHaveRules;
use Nicat\FormFactory\Components\Traits\CanPerformAjaxValidation;
use Nicat\FormFactory\Components\Traits\UsesAutoTranslation;
use Nicat\HtmlFactory\Elements\SelectElement;

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
     * Select constructor.
     * @param string $name
     * @param array $options
     */
    public function __construct(string $name, array $options = [])
    {
        parent::__construct();
        $this->name($name);
        foreach ($options as $option) {
            $this->appendContent($option);
        }
    }


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
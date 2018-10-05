<?php

namespace Nicat\FormFactory\Components\FormControls;

use Nicat\FormFactory\Components\HelpText\HelpTextInterface;
use Nicat\FormFactory\Components\Traits\CanBelongToFormInstance;
use Nicat\FormFactory\Utilities\AutoTranslation\AutoTranslationInterface;
use Nicat\FormFactory\Utilities\FieldValues\FieldValueProcessorInterface;
use Nicat\FormFactory\Components\Traits\CanAutoSubmit;
use Nicat\FormFactory\Components\Traits\CanHaveErrors;
use Nicat\FormFactory\Components\Traits\CanHaveHelpText;
use Nicat\FormFactory\Components\Traits\CanHaveLabel;
use Nicat\FormFactory\Components\Traits\CanHaveRules;
use Nicat\FormFactory\Components\Traits\CanPerformAjaxValidation;
use Nicat\FormFactory\Components\Traits\UsesAutoTranslation;
use Nicat\HtmlFactory\Components\CheckboxInputComponent;

class CheckboxInput extends CheckboxInputComponent implements FieldValueProcessorInterface, AutoTranslationInterface, HelpTextInterface
{
    use CanHaveLabel,
        CanHaveRules,
        CanHaveHelpText,
        UsesAutoTranslation,
        CanHaveErrors,
        CanAutoSubmit,
        CanBelongToFormInstance,
        CanPerformAjaxValidation;

    /**
     * CheckboxInput constructor.
     *
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value="1")
    {
        parent::__construct();
        $this->name($name);
        $this->value($value);
        $this->labelMode('bound');
    }

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
     * Does this field currently have a value set?
     *
     * @return bool
     */
    public function fieldHasValue()
    {
        return $this->attributes->isSet('checked');
    }
}
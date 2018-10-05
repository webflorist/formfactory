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
use Nicat\HtmlFactory\Elements\TextareaElement;

class Textarea extends TextareaElement implements FieldValueProcessorInterface, HelpTextInterface, AutoTranslationInterface
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
     * Textarea constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct();
        $this->name($name);
    }

    /**
     * Apply a value to a field.
     *
     * @param $value
     */
    public function applyFieldValue($value)
    {
        $this->content->clear();
        $this->content($value);
    }

    /**
     * Does this field currently have a value set?
     *
     * @return bool
     */
    public function fieldHasValue()
    {
        return $this->content->hasContent();
    }
}
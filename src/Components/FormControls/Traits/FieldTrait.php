<?php

namespace Webflorist\FormFactory\Components\FormControls\Traits;

use Webflorist\FormFactory\Components\FormControls\HiddenInput;
use Webflorist\FormFactory\Components\Helpers\FieldWrapper;
use Webflorist\FormFactory\FormFactory;
use Webflorist\FormFactory\Components\Helpers\ErrorContainer;
use Webflorist\FormFactory\Components\Form\FieldRules\FieldRuleManager;

/**
 * This traits provides basic functionality for Fields.
 *
 * @package Webflorist\FormFactory
 */
trait FieldTrait
{

    /**
     * The FieldWrapper object used to render this field's wrapper.
     *
     * @var FieldWrapper
     */
    public $wrapper;

    /**
     * The the name of this field.
     *
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->attributes->name;
    }

    /**
     * Can this Field have a label?
     *
     * @return bool
     */
    public function canHaveLabel(): bool
    {
        return $this->is(LabelTrait::class);
    }

    /**
     * Can this Field have errors?
     *
     * @return bool
     */
    public function canHaveErrors(): bool
    {
        return $this->is(ErrorsTrait::class);
    }

    /**
     * Can this Field have rules?
     *
     * @return bool
     */
    public function canHaveRules(): bool
    {
        return $this->is(RulesTrait::class);
    }

    /**
     * Can this Field have a wrapper?
     * (only the hidden input does not have a wrapper)
     *
     * @return bool
     */
    public function canHaveWrapper(): bool
    {
        return !$this->is(HiddenInput::class);
    }

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
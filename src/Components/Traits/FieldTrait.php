<?php

namespace Nicat\FormFactory\Components\Traits;

use Nicat\FormFactory\Components\Helpers\FieldWrapper;
use Nicat\FormFactory\FormFactory;
use Nicat\FormFactory\Components\Helpers\ErrorContainer;
use Nicat\FormFactory\Utilities\FieldRules\FieldRuleManager;

/**
 * This traits provides basic functionality for Fields.
 *
 * @package Nicat\FormFactory
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
     * The ErrorContainer object used to manage errors for this Field.
     *
     * @var ErrorContainer
     */
    public $errors;

    /**
     * Rules for this field.
     *
     * @var null|array
     */
    protected $rules = null;

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
     * Set array of errors for this Field.
     * (Omit for automatic adoption from session)
     * Set to false to avoid rendering of errors.
     *
     * @param array|false $errors
     * @return $this
     */
    public function errors($errors)
    {
        if (is_array($errors)) {
            $this->errors->setErrors($errors);
        }
        if ($errors === false) {
            $this->errors->hideErrors();
        }
        return $this;
    }

    /**
     * Set rules for this field in Laravel-syntax (either in array- or string-format)
     * (omit for automatic adoption from request-object)
     *
     * @param string|array $rules
     * @return $this
     */
    public function rules($rules)
    {
        $this->rules = FieldRuleManager::parseRules($rules);
        return $this;
    }

    /**
     * Does this field have any rules set?
     *
     * @return bool
     * @throws \Nicat\FormFactory\Exceptions\OpenElementNotFoundException
     */
    public function hasRules(): bool
    {
        return count($this->getRules()) > 0;
    }

    /**
     * Get the rules for this field.
     *
     * @return array
     * @throws \Nicat\FormFactory\Exceptions\OpenElementNotFoundException
     */
    public function getRules(): array
    {
        // If no rules were specifically set using the 'rules' method of this field,
        // we try to fill them via the FormFactory service.
        if (is_null($this->rules)) {
            /** @var FormFactory $formFactoryService */
            $formFactoryService = FormFactory::singleton();
            $this->rules = $formFactoryService->getOpenForm()->rules->getRulesForField($this->attributes->name);
        }

        return $this->rules;
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
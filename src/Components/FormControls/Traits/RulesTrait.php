<?php

namespace Webflorist\FormFactory\Components\FormControls\Traits;

use Webflorist\FormFactory\Components\FormControls\HiddenInput;
use Webflorist\FormFactory\Components\Helpers\FieldWrapper;
use Webflorist\FormFactory\FormFactory;
use Webflorist\FormFactory\Components\Helpers\ErrorContainer;
use Webflorist\FormFactory\Components\Form\FieldRules\FieldRuleManager;

/**
 * This traits provides rules functionality for Fields.
 *
 * @package Webflorist\FormFactory
 */
trait RulesTrait
{

    /**
     * Rules for this field.
     *
     * @var null|array
     */
    protected $rules = null;

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
     * @throws \Webflorist\FormFactory\Exceptions\OpenElementNotFoundException
     */
    public function hasRules(): bool
    {
        return count($this->getRules()) > 0;
    }

    /**
     * Get the rules for this field.
     *
     * @return array
     * @throws \Webflorist\FormFactory\Exceptions\OpenElementNotFoundException
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

}
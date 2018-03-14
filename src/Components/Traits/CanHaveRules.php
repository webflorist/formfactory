<?php

namespace Nicat\FormBuilder\Components\Traits;

use Nicat\FormBuilder\Utilities\FieldRules\FieldRuleManager;
use Nicat\FormBuilder\FormBuilder;

trait CanHaveRules
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
     */
    public function hasRules() : bool
    {
        return count($this->getRules()) > 0;
    }

    /**
     * Get the rules for this field.
     *
     * @return array
     */
    public function getRules() : array
    {
        // If no rules were specifically set using the 'rules' method of this field,
        // we try to fill them via the FormBuilder service.
        if (is_null($this->rules)) {
            /** @var FormBuilder $formBuilderService */
            $formBuilderService = app(FormBuilder::class);
            $this->rules =  $formBuilderService->openForm->rules->getRulesForField($this->attributes->name);
        }

        return $this->rules;
    }


}
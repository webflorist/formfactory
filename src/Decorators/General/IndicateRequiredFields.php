<?php

namespace Nicat\FormBuilder\Decorators\General;

use Nicat\FormBuilder\Components\Additional\RadioGroup;
use Nicat\FormBuilder\Components\Additional\RequiredFieldIndicator;
use Nicat\FormBuilder\Components\FormControls\RadioInput;
use Nicat\FormBuilder\Components\Traits\CanHaveLabel;
use Nicat\FormBuilder\Components\Traits\CanHaveRules;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;
use Nicat\HtmlBuilder\Elements\Abstracts\Element;

/**
 * Adds an indication to the label of required form fields.
 *
 * Class DecorateFields
 * @package Nicat\FormBuilder\Decorators\General
 */
class IndicateRequiredFields extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var Element|CanHaveLabel
     */
    protected $element;

    /**
     * Returns an array of frontend-framework-ids, this decorator is specific for.
     * Returning an empty array means all frameworks are supported.
     *
     * @return string[]
     */
    public static function getSupportedFrameworks(): array
    {
        return [];
    }

    /**
     * Returns an array of class-names of elements, that should be decorated by this decorator.
     *
     * @return string[]
     */
    public static function getSupportedElements(): array
    {
        return array_merge(DecorateFields::getSupportedElements(),[RadioGroup::class]);
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {

        if ($this->element->is(RadioGroup::class)) {
            $this->indicateRadioGroup();
        }

        if (!$this->element->is(RadioInput::class) && method_exists($this->element,'label')) {
            $this->indicateField();
        }

    }

    /**
     * Adds indication to normal fields
     */
    protected function indicateField()
    {
        if (!is_null($this->element->label) && $this->element->attributes->isSet('required')) {
            $this->element->label(
                $this->element->label . new RequiredFieldIndicator()
            );
        }
    }

    /**
     * Adds indication to radio-groups.
     */
    protected function indicateRadioGroup()
    {
        /** @var RadioGroup $radioGroup */
        $radioGroup = $this->element;
        foreach ($radioGroup->getChildrenByClassName(RadioInput::class) as $radioElement) {
            if ($this->isFieldRequired($radioElement)) {
                $radioGroup->legend(
                    $radioGroup->legend . new RequiredFieldIndicator()
                );
                break;
            }
        }
    }

    /**
     * Checks, if a field is required by having the 'required' rule set.
     *
     * @param CanHaveRules|Element $field
     * @return bool
     */
    protected function isFieldRequired($field) {
        return array_key_exists('required',$field->getRules()) || $field->attributes->isSet('required');
    }

}
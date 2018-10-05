<?php

namespace Nicat\FormFactory\Decorators\General;

use Nicat\FormFactory\Components\Additional\RadioGroup;
use Nicat\FormFactory\Components\Additional\RequiredFieldIndicator;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\FormFactory\Components\Traits\CanHaveLabel;
use Nicat\FormFactory\Components\Traits\CanHaveRules;
use Nicat\FormFactory\Utilities\ComponentLists;
use Nicat\FormFactory\Utilities\Config\FormFactoryConfig;
use Nicat\HtmlFactory\Decorators\Abstracts\Decorator;
use Nicat\HtmlFactory\Elements\Abstracts\Element;

/**
 * Adds an indication to the label of required form fields.
 *
 * Class DecorateFields
 * @package Nicat\FormFactory\Decorators\General
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
     * Returns the group-ID of this decorator.
     *
     * Returning null means this decorator will always be applied.
     *
     * @return string|null
     */
    public static function getGroupId()
    {
        return null;
    }

    /**
     * Returns an array of class-names of elements, that should be decorated by this decorator.
     *
     * @return string[]
     */
    public static function getSupportedElements(): array
    {
        return ComponentLists::labelableFields();
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {

        if ($this->element->is(RadioGroup::class)) {
            $this->indicateRadioGroup();
        }

        if (!$this->element->is(RadioInput::class)) {
            $this->indicateField();
        }

    }

    /**
     * Adds indication to normal fields
     */
    protected function indicateField()
    {

        // If field has no displayable label, we do nothing.
        if (!$this->doesFieldHaveDisplayableLabel($this->element)) {
            return;
        }

        // If the field is not required, we also do nothing.
        // The only exception is, if vue is being used. In this case a required field-indicator is always added.
        // TODO: this is smelly. think of better solution.
        if (!$this->isFieldRequired($this->element) && !FormFactoryConfig::isVueEnabled()) {
            return;
        }

        // Otherwise we append the RequiredFieldIndicator to the label-text.
        $this->element->label(
            $this->element->label . new RequiredFieldIndicator($this->element)
        );
    }

    /**
     * Adds indication to radio-groups.
     */
    protected function indicateRadioGroup()
    {
        /** @var RadioGroup $radioGroup */
        $radioGroup = $this->element;
        foreach ($radioGroup->content->getChildrenByClassName(RadioInput::class) as $radioElement) {

            if ($this->isFieldRequired($radioElement) && !is_null($radioGroup->legend) && ($radioGroup->legend !== false)) {
                $radioGroup->legend(
                    $radioGroup->legend . new RequiredFieldIndicator($radioElement)
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
     * @throws \Nicat\FormFactory\Exceptions\OpenElementNotFoundException
     */
    protected function isFieldRequired($field)
    {
        return array_key_exists('required', $field->getRules()) || $field->attributes->isSet('required');
    }

    /**
     * Checks, if a field is required by having the 'required' rule set.
     *
     * @param CanHaveLabel|Element $field
     * @return bool
     */
    private function doesFieldHaveDisplayableLabel($field)
    {

        if (!method_exists($field, 'label')) {
            return false;
        }

        if (is_null($field->label)) {
            return false;
        }

        if ($field->labelMode === 'none') {
            return false;
        }

        return true;

    }

}
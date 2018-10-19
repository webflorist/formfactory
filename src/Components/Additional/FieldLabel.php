<?php

namespace Nicat\FormFactory\Components\Additional;

use Nicat\FormFactory\Components\FormControls\CheckboxInput;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\FormFactory\Components\Traits\CanHaveRules;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\LabelElement;
use Nicat\FormFactory\Components\Traits\CanHaveLabel;
use Nicat\FormFactory\Utilities\Config\FormFactoryConfig;

class FieldLabel extends LabelElement
{

    /**
     * The field this FieldLabel belongs to.
     *
     * @var Element
     */
    public $field;

    /**
     * The label-text.
     *
     * @var string
     */
    public $text;

    /**
     * FieldLabel constructor.
     *
     * @param Element|null $field
     */
    public function __construct($field = null)
    {
        parent::__construct();
        $this->field = $field;
    }

    /**
     * Sets the label-text.
     *
     * @param $text
     * @return FieldLabel
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Gets called after applying decorators.
     * Overwrite to perform manipulations.
     *
     * @throws \Nicat\FormFactory\Exceptions\OpenElementNotFoundException
     */
    protected function afterDecoration()
    {
        if (!is_null($this->field) && $this->field->labelMode !== 'none') {

            // For fields other than Radio and Checkbox, we add the 'for'-attribute.
            if (!$this->field->is(RadioInput::class) && !$this->field->is(CheckboxInput::class)) {
                $this->for($this->field->attributes->id);
            }

            // Set the label-text as content.
            $this->content($this->text);

            // Indicate the field as required, if needed.
            $this->indicateRequiredField();

            // Set the 'sr-only' class, if field's label-mode says.
            if ($this->field->labelMode === 'sr-only') {
                $this->addClass('sr-only');
            }
        }
    }

    /**
     * Render the element to a string.
     *
     * @return string
     */
    protected function render(): string
    {
        // We only render this FieldLabel, if it actually has content.
        if (!$this->content->hasContent()) {
            return '';
        }
        return parent::render();

    }

    /**
     * @throws \Nicat\FormFactory\Exceptions\OpenElementNotFoundException
     */
    private function indicateRequiredField()
    {
        if ($this->field->is(RadioGroup::class)) {
            $this->indicateRadioGroup();
        }

        if (!$this->field->is(RadioInput::class)) {
            $this->indicateField();
        }
    }


    /**
     * Adds indication to normal fields
     * 
     * @throws \Nicat\FormFactory\Exceptions\OpenElementNotFoundException
     */
    protected function indicateField()
    {

        // If field has no displayable label, we do nothing.
        if (!$this->doesFieldHaveDisplayableLabel($this->field)) {
            return;
        }

        // If the field is not required, we also do nothing.
        // The only exception is, if vue is being used. In this case a required field-indicator is always added.
        // TODO: this is smelly. think of better solution.
        if (!$this->isFieldRequired($this->field) && !FormFactoryConfig::isVueEnabled()) {
            return;
        }

        // Otherwise we append the RequiredFieldIndicator to the label-text.
        $this->appendContent( new RequiredFieldIndicator($this->field));
    }

    /**
     * Adds indication to radio-groups.
     * 
     * @throws \Nicat\FormFactory\Exceptions\OpenElementNotFoundException
     */
    protected function indicateRadioGroup()
    {
        /** @var RadioGroup $radioGroup */
        $radioGroup = $this->field;
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
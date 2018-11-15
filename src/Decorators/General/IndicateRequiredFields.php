<?php

namespace Webflorist\FormFactory\Decorators\General;

use Webflorist\FormFactory\Components\Additional\RadioGroup;
use Webflorist\FormFactory\Components\Additional\RequiredFieldIndicator;
use Webflorist\FormFactory\Components\FormControls\RadioInput;
use Webflorist\FormFactory\Components\Traits\CanHaveLabel;
use Webflorist\FormFactory\Components\Traits\CanHaveRules;
use Webflorist\HtmlFactory\Decorators\Abstracts\Decorator;
use Webflorist\HtmlFactory\Elements\Abstracts\Element;
use Webflorist\FormFactory\Components\FormControls\MonthInput;
use Webflorist\FormFactory\Components\FormControls\PasswordInput;
use Webflorist\FormFactory\Components\FormControls\RangeInput;
use Webflorist\FormFactory\Components\FormControls\SearchInput;
use Webflorist\FormFactory\Components\FormControls\TelInput;
use Webflorist\FormFactory\Components\FormControls\TimeInput;
use Webflorist\FormFactory\Components\FormControls\UrlInput;
use Webflorist\FormFactory\Components\FormControls\WeekInput;
use Webflorist\FormFactory\Components\FormControls\CheckboxInput;
use Webflorist\FormFactory\Components\FormControls\ColorInput;
use Webflorist\FormFactory\Components\FormControls\DateInput;
use Webflorist\FormFactory\Components\FormControls\DatetimeInput;
use Webflorist\FormFactory\Components\FormControls\DatetimeLocalInput;
use Webflorist\FormFactory\Components\FormControls\EmailInput;
use Webflorist\FormFactory\Components\FormControls\FileInput;
use Webflorist\FormFactory\Components\FormControls\NumberInput;
use Webflorist\FormFactory\Components\FormControls\Select;
use Webflorist\FormFactory\Components\FormControls\Textarea;
use Webflorist\FormFactory\Components\FormControls\TextInput;

/**
 * Adds an indication to the label of required form fields.
 *
 * Class DecorateFields
 * @package Webflorist\FormFactory\Decorators\General
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
        return [
            CheckboxInput::class,
            ColorInput::class,
            DateInput::class,
            DatetimeInput::class,
            DatetimeLocalInput::class,
            EmailInput::class,
            FileInput::class,
            RadioGroup::class,
            MonthInput::class,
            NumberInput::class,
            PasswordInput::class,
            RadioInput::class,
            RangeInput::class,
            SearchInput::class,
            Select::class,
            TelInput::class,
            Textarea::class,
            TextInput::class,
            TimeInput::class,
            UrlInput::class,
            WeekInput::class
        ];
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
        if (!$this->isFieldRequired($this->element)) {
            return;
        }

        // Otherwise we append the RequiredFieldIndicator to the label-text.
        $this->element->label(
            $this->element->label . new RequiredFieldIndicator()
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
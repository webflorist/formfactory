<?php

namespace Nicat\FormBuilder;


use Nicat\FormBuilder\Elements\CheckboxInputElement;
use Nicat\FormBuilder\Elements\ColorInputElement;
use Nicat\FormBuilder\Elements\DateInputElement;
use Nicat\FormBuilder\Elements\DatetimeInputElement;
use Nicat\FormBuilder\Elements\DatetimeLocalInputElement;
use Nicat\FormBuilder\Elements\EmailInputElement;
use Nicat\FormBuilder\Elements\FormElement;
use Nicat\FormBuilder\Elements\HiddenInputElement;
use Nicat\FormBuilder\Elements\NumberInputElement;
use Nicat\FormBuilder\Elements\TextareaElement;
use Nicat\FormBuilder\Elements\TextInputElement;

/**
 * The main class of this package.
 * Provides factory methods for all form-tags.
 *
 * Class FormBuilder
 * @package Nicat\FormBuilder
 *
 *
 */
class FormBuilder
{

    /**
     * The currently open FormElement.
     *
     * @var FormElement
     */
    public $currentForm = null;

    /**
     * Generates and returns the opening form-tag.
     * Also sets the form as $this->currentForm.
     *
     * @param string $id
     * @return FormElement
     */
    public function open(string $id): FormElement
    {
        $formElement = (new FormElement())->id($id);
        $this->currentForm = $formElement;
        return $formElement;
    }

    /**
     * Creates the closing-tag of the form
     *
     * @param bool $showMandatoryFieldsLegend
     * @return string
     */
    public function close(bool $showMandatoryFieldsLegend = true)
    {
        $return = '';
        if ($showMandatoryFieldsLegend && $this->currentForm) {
            $return .= '<div class="text-muted small"><sup>*</sup> ' . trans('Nicat-FormBuilder::formbuilder.mandatoryFields') . '</div>';
        }
        $return .= '</form>';
        $this->currentForm = null;
        return $return;
    }

    /**
     * Generates form-control '<input type="text" />'
     *
     * @param string $name
     * @return TextInputElement
     */
    public function text(string $name): TextInputElement
    {
        return (new TextInputElement())->name($name);
    }

    /**
     * Generates form-control '<textarea></textarea>'
     *
     * @param string $name
     * @return TextareaElement
     */
    public function textarea(string $name): TextareaElement
    {
        return (new TextareaElement())->name($name);
    }

    /**
     * Generates form-control '<input type="number" />'
     *
     * @param string $name
     * @return NumberInputElement
     */
    public function number(string $name): NumberInputElement
    {
        return (new NumberInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="color" />'
     *
     * @param string $name
     * @return ColorInputElement
     */
    public function color(string $name): ColorInputElement
    {
        return (new ColorInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="date" />'
     *
     * @param string $name
     * @return DateInputElement
     */
    public function date(string $name): DateInputElement
    {
        return (new DateInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="datetime" />'
     *
     * @param string $name
     * @return DatetimeInputElement
     */
    public function datetime(string $name): DatetimeInputElement
    {
        return (new DatetimeInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="datetime-local" />'
     *
     * @param string $name
     * @return DatetimeLocalInputElement
     */
    public function datetimeLocal(string $name): DatetimeLocalInputElement
    {
        return (new DatetimeLocalInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="email" />'
     *
     * @param string $name
     * @return EmailInputElement
     */
    public function email(string $name): EmailInputElement
    {
        return (new EmailInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="hidden" />'
     *
     * @param string $name
     * @return HiddenInputElement
     */
    public function hidden(string $name): HiddenInputElement
    {
        return (new HiddenInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="checkbox" />'
     *
     * @param string $name
     * @param string $value
     * @return CheckboxInputElement
     */
    public function checkbox(string $name, string $value): CheckboxInputElement
    {
        return (new CheckboxInputElement())->name($name)->value($value)->labelMode('bound');
    }

}
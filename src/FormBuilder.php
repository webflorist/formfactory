<?php

namespace Nicat\FormBuilder;


use Nicat\FormBuilder\Elements\ButtonElement;
use Nicat\FormBuilder\Elements\CheckboxInputElement;
use Nicat\FormBuilder\Elements\ColorInputElement;
use Nicat\FormBuilder\Elements\DateInputElement;
use Nicat\FormBuilder\Elements\DatetimeInputElement;
use Nicat\FormBuilder\Elements\DatetimeLocalInputElement;
use Nicat\FormBuilder\Elements\EmailInputElement;
use Nicat\FormBuilder\Elements\FileInputElement;
use Nicat\FormBuilder\Elements\FormElement;
use Nicat\FormBuilder\Elements\HiddenInputElement;
use Nicat\FormBuilder\Elements\NumberInputElement;
use Nicat\FormBuilder\Elements\OptionElement;
use Nicat\FormBuilder\Elements\RadioInputElement;
use Nicat\FormBuilder\Elements\ResetButtonElement;
use Nicat\FormBuilder\Elements\SelectElement;
use Nicat\FormBuilder\Elements\SubmitButtonElement;
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
    public $openForm = null;

    /**
     * The currently open select-box.
     *
     * @var SelectElement
     */
    public $openSelect = null;

    /**
     * Generates and returns the opening form-tag.
     * Also sets the form as $this->openForm.
     *
     * @param string $id
     * @return FormElement
     */
    public function open(string $id): FormElement
    {
        $formElement = (new FormElement())->id($id)->method('post');
        $this->openForm = $formElement;
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
        if ($showMandatoryFieldsLegend && $this->openForm) {
            $return .= '<div class="text-muted small"><sup>*</sup> ' . trans('Nicat-FormBuilder::formbuilder.mandatory_fields') . '</div>';
        }
        $return .= '</form>';
        $this->openForm = null;
        return $return;
    }

    /**
     * Generates form-control '<input type="text" />'.
     *
     * @param string $name
     * @return TextInputElement
     */
    public function text(string $name): TextInputElement
    {
        return (new TextInputElement())->name($name);
    }

    /**
     * Generates form-control '<textarea></textarea>'.
     *
     * @param string $name
     * @return TextareaElement
     */
    public function textarea(string $name): TextareaElement
    {
        return (new TextareaElement())->name($name);
    }

    /**
     * Generates form-control '<input type="number" />'.
     *
     * @param string $name
     * @return NumberInputElement
     */
    public function number(string $name): NumberInputElement
    {
        return (new NumberInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="color" />'.
     *
     * @param string $name
     * @return ColorInputElement
     */
    public function color(string $name): ColorInputElement
    {
        return (new ColorInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="date" />'.
     *
     * @param string $name
     * @return DateInputElement
     */
    public function date(string $name): DateInputElement
    {
        return (new DateInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="datetime" />'.
     *
     * @param string $name
     * @return DatetimeInputElement
     */
    public function datetime(string $name): DatetimeInputElement
    {
        return (new DatetimeInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="datetime-local" />'.
     *
     * @param string $name
     * @return DatetimeLocalInputElement
     */
    public function datetimeLocal(string $name): DatetimeLocalInputElement
    {
        return (new DatetimeLocalInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="email" />'.
     *
     * @param string $name
     * @return EmailInputElement
     */
    public function email(string $name): EmailInputElement
    {
        return (new EmailInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="hidden" />'.
     *
     * @param string $name
     * @return HiddenInputElement
     */
    public function hidden(string $name): HiddenInputElement
    {
        return (new HiddenInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="file" />'.
     *
     * @param string $name
     * @return FileInputElement
     */
    public function file(string $name): FileInputElement
    {
        return (new FileInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="checkbox" />'.
     *
     * @param string $name
     * @param string $value
     * @return CheckboxInputElement
     */
    public function checkbox(string $name, string $value): CheckboxInputElement
    {
        return (new CheckboxInputElement())->name($name)->value($value)->labelMode('bound');
    }

    /**
     * Generates form-control '<input type="radio" />'.
     *
     * @param string $value
     * @param string $name
     * @return RadioInputElement
     */
    public function radio(string $value, string $name): RadioInputElement
    {
        return (new RadioInputElement())->name($name)->value($value)->labelMode('bound');
    }

    /**
     * Generates form-control '<option></option>'.
     *
     * @param string $value
     * @return OptionElement
     */
    public function option(string $value): OptionElement
    {
        return (new OptionElement())->value($value);
    }

    /**
     * Generates form-control '<select></select>'.
     * Also sets the selectElement as $this->openSelect.
     *
     * @param string $name
     * @param array $options
     * @return SelectElement
     */
    public function select(string $name,array $options=[]): SelectElement
    {
        $selectElement = (new SelectElement())->name($name);
        $this->openSelect = $selectElement;
        foreach ($options as $option) {
            $selectElement->appendChild($option);
        }
        return $selectElement;
    }

    /**
     * Generates form-control '<button type="reset"></button>'.
     *
     * @param string $name
     * @return ResetButtonElement
     */
    public function reset(string $name='reset'): ResetButtonElement
    {
        return (new ResetButtonElement())->name($name);
    }

    /**
     * Generates form-control '<button type="submit"></button>'.
     *
     * @param string $name
     * @return SubmitButtonElement
     */
    public function submit(string $name='submit'): SubmitButtonElement
    {
        return (new SubmitButtonElement())->name($name);
    }

    /**
     * Generates form-control '<button></button>'.
     *
     * @param string $name
     * @return ButtonElement
     */
    public function button(string $name=''): ButtonElement
    {
        return (new ButtonElement())->name($name);
    }

}
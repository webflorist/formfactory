<?php

namespace Nicat\FormBuilder;


use Nicat\FormBuilder\Components\DynamicList;
use Nicat\FormBuilder\Components\InputGroupAddonComponent;
use Nicat\FormBuilder\Components\InputGroupButtonComponent;
use Nicat\FormBuilder\Components\InputGroupComponent;
use Nicat\FormBuilder\Components\PanelComponent;
use Nicat\FormBuilder\Elements\ButtonElement;
use Nicat\FormBuilder\Elements\CheckboxInputElement;
use Nicat\FormBuilder\Elements\ColorInputElement;
use Nicat\FormBuilder\Components\Contracts\DynamicListTemplateInterface;
use Nicat\FormBuilder\Elements\DateInputElement;
use Nicat\FormBuilder\Elements\DatetimeInputElement;
use Nicat\FormBuilder\Elements\DatetimeLocalInputElement;
use Nicat\FormBuilder\Elements\EmailInputElement;
use Nicat\FormBuilder\Elements\FileInputElement;
use Nicat\FormBuilder\Elements\FormElement;
use Nicat\FormBuilder\Elements\HiddenInputElement;
use Nicat\FormBuilder\Elements\NumberInputElement;
use Nicat\FormBuilder\Elements\OptgroupElement;
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
     * Also sets the form as app(FormBuilder::class)->openForm.
     *
     * @param string $id
     * @return FormElement
     * @throws \Nicat\HtmlBuilder\Exceptions\AttributeNotAllowedException
     * @throws \Nicat\HtmlBuilder\Exceptions\AttributeNotFoundException
     */
    public static function open(string $id): FormElement
    {
        $formElement = (new FormElement())->id($id)->method('post');
        app(FormBuilder::class)->openForm = $formElement;
        return $formElement;
    }

    /**
     * Creates the closing-tag of the form
     *
     * @param bool $showMandatoryFieldsLegend
     * @return string
     */
    public static function close(bool $showMandatoryFieldsLegend = true)
    {
        $return = '';
        if ($showMandatoryFieldsLegend && app(FormBuilder::class)->openForm) {
            $return .= '<div class="text-muted small"><sup>*</sup> ' . trans('Nicat-FormBuilder::formbuilder.mandatory_fields') . '</div>';
        }
        $return .= '</form>';
        app(FormBuilder::class)->openForm = null;
        return $return;
    }

    /**
     * Generates form-control '<input type="text" />'.
     *
     * @param string $name
     * @return TextInputElement
     */
    public static function text(string $name): TextInputElement
    {
        return (new TextInputElement())->name($name);
    }

    /**
     * Generates form-control '<textarea></textarea>'.
     *
     * @param string $name
     * @return TextareaElement
     */
    public static function textarea(string $name): TextareaElement
    {
        return (new TextareaElement())->name($name);
    }

    /**
     * Generates form-control '<input type="number" />'.
     *
     * @param string $name
     * @return NumberInputElement
     */
    public static function number(string $name): NumberInputElement
    {
        return (new NumberInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="color" />'.
     *
     * @param string $name
     * @return ColorInputElement
     */
    public static function color(string $name): ColorInputElement
    {
        return (new ColorInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="date" />'.
     *
     * @param string $name
     * @return DateInputElement
     */
    public static function date(string $name): DateInputElement
    {
        return (new DateInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="datetime" />'.
     *
     * @param string $name
     * @return DatetimeInputElement
     */
    public static function datetime(string $name): DatetimeInputElement
    {
        return (new DatetimeInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="datetime-local" />'.
     *
     * @param string $name
     * @return DatetimeLocalInputElement
     */
    public static function datetimeLocal(string $name): DatetimeLocalInputElement
    {
        return (new DatetimeLocalInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="email" />'.
     *
     * @param string $name
     * @return EmailInputElement
     */
    public static function email(string $name): EmailInputElement
    {
        return (new EmailInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="hidden" />'.
     *
     * @param string $name
     * @return HiddenInputElement
     */
    public static function hidden(string $name): HiddenInputElement
    {
        return (new HiddenInputElement())->name($name);
    }

    /**
     * Generates form-control '<input type="file" />'.
     *
     * @param string $name
     * @return FileInputElement
     */
    public static function file(string $name): FileInputElement
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
    public static function checkbox(string $name, string $value): CheckboxInputElement
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
    public static function radio(string $value, string $name): RadioInputElement
    {
        return (new RadioInputElement())->name($name)->value($value)->labelMode('bound');
    }

    /**
     * Generates form-control '<option></option>'.
     *
     * @param string $value
     * @return OptionElement
     */
    public static function option(string $value): OptionElement
    {
        return (new OptionElement())->value($value);
    }

    /**
     * Generates form-control '<optgroup></optgroup>'.
     *
     * @param string $label
     * @param OptionElement[] $options
     * @return OptgroupElement
     */
    public static function optgroup($label='',$options) {
        return (new OptgroupElement())->content($options);
    }

    /**
     * Generates form-control '<select></select>'.
     * Also sets the selectElement as app(FormBuilder::class)->openSelect.
     *
     * @param string $name
     * @param array $options
     * @return SelectElement
     */
    public static function select(string $name,array $options=[]): SelectElement
    {
        $selectElement = (new SelectElement())->name($name);
        app(FormBuilder::class)->openSelect = $selectElement;
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
    public static function reset(string $name='reset'): ResetButtonElement
    {
        return (new ResetButtonElement())->name($name);
    }

    /**
     * Generates form-control '<button type="submit"></button>'.
     *
     * @param string $name
     * @return SubmitButtonElement
     */
    public static function submit(string $name='submit'): SubmitButtonElement
    {
        return (new SubmitButtonElement())->name($name);
    }

    /**
     * Generates form-control '<button></button>'.
     *
     * @param string $name
     * @return ButtonElement
     */
    public static function button(string $name=''): ButtonElement
    {
        return (new ButtonElement())->name($name);
    }

    /**
     * Generates a DynamicList.
     *
     * @param string $arrayName: The base-array-name of all fields within this dynamic list (e.g. "users" or "users[][emails]")
     * @param DynamicListTemplateInterface $template: An element/component, that can be a DynamicListTemplate (must implement DynamicListTemplateInterface)
     * @param null $addButtonLabel: The label for the button to add a new item. (Gets auto-translated, if possible.)
     * @param null $minItems: Minimum items of this dynamic list. (Gets auto-fetched from rules, if possible.)
     * @param null $maxItems: Maximum items of this dynamic list. (Gets auto-fetched from rules, if possible.)
     * @return DynamicList
     */
    public static function dynamicList($arrayName, DynamicListTemplateInterface $template, $addButtonLabel=null, $minItems=null, $maxItems=null) : DynamicList
    {
        return new DynamicList($arrayName, $template, $addButtonLabel, $minItems, $maxItems);
    }

    /**
     * Generates PanelComponent.
     *
     * @return PanelComponent
     */
    public static function panel(): PanelComponent
    {
        return (new PanelComponent());
    }

    /**
     * Generates InputGroupComponent.
     *
     * @return InputGroupComponent
     */
    public static function inputGroup(): InputGroupComponent
    {
        return (new InputGroupComponent());
    }

    /**
     * Generates InputGroupButtonComponent
     *
     * @param \Nicat\HtmlBuilder\Elements\ButtonElement $button
     * @return InputGroupButtonComponent
     */
    public static function inputGroupButton(\Nicat\HtmlBuilder\Elements\ButtonElement $button) {
        return new InputGroupButtonComponent($button);
    }

    /**
     * Generates Input-Group-Addon
     *
     * @param string|CheckboxInputElement|RadioInputElement $content
     * @return InputGroupAddonComponent
     */
    public static function inputGroupAddon($content) {
        return new InputGroupAddonComponent($content);
    }


}
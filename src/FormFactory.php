<?php

namespace Nicat\FormFactory;

use Nicat\FormFactory\Components\Additional\ButtonGroup;
use Nicat\FormFactory\Components\Additional\ErrorContainer;
use Nicat\FormFactory\Components\Additional\RequiredFieldIndicator;
use Nicat\FormFactory\Components\DynamicLists\DynamicList;
use Nicat\FormFactory\Components\Additional\InputGroupAddon;
use Nicat\FormFactory\Components\Additional\InputGroupButton;
use Nicat\FormFactory\Components\Additional\InputGroup;
use Nicat\FormFactory\Components\Additional\Panel;
use Nicat\FormFactory\Components\Additional\RadioGroup;
use Nicat\FormFactory\Components\Additional\RequiredFieldsLegend;
use Nicat\FormFactory\Components\FormControls\Button;
use Nicat\FormFactory\Components\FormControls\CheckboxInput;
use Nicat\FormFactory\Components\FormControls\ColorInput;
use Nicat\FormFactory\Components\DynamicLists\DynamicListTemplateInterface;
use Nicat\FormFactory\Components\FormControls\DateInput;
use Nicat\FormFactory\Components\FormControls\DatetimeInput;
use Nicat\FormFactory\Components\FormControls\DatetimeLocalInput;
use Nicat\FormFactory\Components\FormControls\EmailInput;
use Nicat\FormFactory\Components\FormControls\FileInput;
use Nicat\FormFactory\Components\Form;
use Nicat\FormFactory\Components\FormControls\HiddenInput;
use Nicat\FormFactory\Components\FormControls\MonthInput;
use Nicat\FormFactory\Components\FormControls\NumberInput;
use Nicat\FormFactory\Components\FormControls\Optgroup;
use Nicat\FormFactory\Components\FormControls\Option;
use Nicat\FormFactory\Components\FormControls\PasswordInput;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\FormFactory\Components\FormControls\RangeInput;
use Nicat\FormFactory\Components\FormControls\ResetButton;
use Nicat\FormFactory\Components\FormControls\SearchInput;
use Nicat\FormFactory\Components\FormControls\Select;
use Nicat\FormFactory\Components\FormControls\SubmitButton;
use Nicat\FormFactory\Components\FormControls\TelInput;
use Nicat\FormFactory\Components\FormControls\Textarea;
use Nicat\FormFactory\Components\FormControls\TextInput;
use Nicat\FormFactory\Components\FormControls\TimeInput;
use Nicat\FormFactory\Components\FormControls\UrlInput;
use Nicat\FormFactory\Components\FormControls\WeekInput;
use Nicat\FormFactory\Exceptions\OpenElementNotFoundException;
use Nicat\HtmlFactory\Elements\ButtonElement;
use Nicat\HtmlFactory\Elements\FieldsetElement;

/**
 * The main class of this package.
 * Provides factory methods for all form-tags.
 *
 * Class FormFactory
 * @package Nicat\FormFactory
 *
 *
 */
class FormFactory
{

    /**
     * The currently open Form.
     *
     * @var Form
     */
    protected $openForm = null;

    /**
     * The currently open Select.
     *
     * @var Select
     */
    protected $openSelect = null;

    /**
     * Has a required-field-indicator been rendered,
     * and thus should the required-fields-legend be displayed?
     *
     * @var bool
     */
    public $requiredFieldIndicatorUsed = false;

    /**
     * Generates and returns the opening form-tag.
     * Also sets the form as app(FormFactory::class)->openForm.
     *
     * @param string $id
     * @return Form
     * @throws \Nicat\HtmlFactory\Exceptions\AttributeNotAllowedException
     * @throws \Nicat\HtmlFactory\Exceptions\AttributeNotFoundException
     */
    public static function open(string $id): Form
    {
        $form = (new Form())->id($id)->method('post');
        app(FormFactory::class)->openForm = $form;
        app(FormFactory::class)->requiredFieldIndicatorUsed = false;
        return $form;
    }

    /**
     * Creates the closing-tag of the form
     *
     * @param bool $showRequiredFieldsLegend
     * @return string
     */
    public static function close(bool $showRequiredFieldsLegend = true)
    {
        $return = '';
        if ($showRequiredFieldsLegend && app(FormFactory::class)->requiredFieldIndicatorUsed) {
            $return .= new RequiredFieldsLegend();
        }
        $return .= '</form>';
        app(FormFactory::class)->openForm = null;
        app(FormFactory::class)->requiredFieldIndicatorUsed = false;
        return $return;
    }

    /**
     * Generates form-control '<input type="checkbox" />'.
     *
     * @param string $name
     * @param string $value
     * @return CheckboxInput
     */
    public static function checkbox(string $name, string $value="1"): CheckboxInput
    {
        return (new CheckboxInput())->name($name)->value($value)->labelMode('bound');
    }

    /**
     * Generates form-control '<input type="color" />'.
     *
     * @param string $name
     * @return ColorInput
     */
    public static function color(string $name): ColorInput
    {
        return (new ColorInput())->name($name);
    }

    /**
     * Generates form-control '<input type="date" />'.
     *
     * @param string $name
     * @return DateInput
     */
    public static function date(string $name): DateInput
    {
        return (new DateInput())->name($name);
    }

    /**
     * Generates form-control '<input type="datetime" />'.
     *
     * @param string $name
     * @return DatetimeInput
     */
    public static function datetime(string $name): DatetimeInput
    {
        return (new DatetimeInput())->name($name);
    }

    /**
     * Generates form-control '<input type="datetime-local" />'.
     *
     * @param string $name
     * @return DatetimeLocalInput
     */
    public static function datetimeLocal(string $name): DatetimeLocalInput
    {
        return (new DatetimeLocalInput())->name($name);
    }

    /**
     * Generates form-control '<input type="email" />'.
     *
     * @param string $name
     * @return EmailInput
     */
    public static function email(string $name): EmailInput
    {
        return (new EmailInput())->name($name);
    }

    /**
     * Generates form-control '<input type="file" />'.
     *
     * @param string $name
     * @return FileInput
     */
    public static function file(string $name): FileInput
    {
        return (new FileInput())->name($name);
    }

    /**
     * Generates form-control '<input type="hidden" />'.
     *
     * @param string $name
     * @return HiddenInput
     */
    public static function hidden(string $name): HiddenInput
    {
        return (new HiddenInput())->name($name);
    }

    /**
     * Generates form-control '<input type="month" />'.
     *
     * @param string $name
     * @return MonthInput
     */
    public static function month(string $name): MonthInput
    {
        return (new MonthInput())->name($name);
    }

    /**
     * Generates form-control '<input type="number" />'.
     *
     * @param string $name
     * @return NumberInput
     */
    public static function number(string $name): NumberInput
    {
        return (new NumberInput())->name($name);
    }

    /**
     * Generates form-control '<optgroup></optgroup>'.
     *
     * @param string $label
     * @param Option[] $options
     * @return Optgroup
     */
    public static function optgroup($label, array $options): Optgroup
    {
        return (new Optgroup())->label($label)->content($options);
    }


    /**
     * Generates form-control '<option></option>'.
     *
     * @param string $value
     * @return Option
     */
    public static function option(string $value = ''): Option
    {
        return (new Option())->value($value);
    }

    /**
     * Generates form-control '<input type="password" />'.
     *
     * @param string $name
     * @return PasswordInput
     */
    public static function password(string $name): PasswordInput
    {
        return (new PasswordInput())->name($name);
    }

    /**
     * Generates form-control '<input type="radio" />'.
     *
     * @param string $value
     * @param string $name
     * @return RadioInput
     */
    public static function radio(string $value, string $name = ''): RadioInput
    {
        return (new RadioInput())->name($name)->value($value)->labelMode('bound');
    }

    /**
     * Generates RadioGroup.
     *
     * @param string $name
     * @param RadioInput[] $children
     * @return RadioGroup
     */
    public static function radioGroup(string $name, array $children) : RadioGroup
    {
        return new RadioGroup($name, $children);
    }

    /**
     * Generates form-control '<input type="range" />'.
     *
     * @param string $name
     * @return RangeInput
     */
    public static function range(string $name): RangeInput
    {
        return (new RangeInput())->name($name);
    }

    /**
     * Generates form-control '<input type="search" />'.
     *
     * @param string $name
     * @return SearchInput
     */
    public static function search(string $name): SearchInput
    {
        return (new SearchInput())->name($name);
    }

    /**
     * Generates form-control '<select></select>'.
     * Also sets the Select as app(FormFactory::class)->openSelect.
     *
     * @param string $name
     * @param array $options
     * @return Select
     */
    public static function select(string $name, array $options = []): Select
    {
        $select = (new Select())->name($name);
        app(FormFactory::class)->openSelect = $select;
        foreach ($options as $option) {
            $select->appendContent($option);
        }
        return $select;
    }

    /**
     * Generates form-control '<input type="tel" />'.
     *
     * @param string $name
     * @return TelInput
     */
    public static function tel(string $name): TelInput
    {
        return (new TelInput())->name($name);
    }

    /**
     * Generates form-control '<textarea></textarea>'.
     *
     * @param string $name
     * @return Textarea
     */
    public static function textarea(string $name): Textarea
    {
        return (new Textarea())->name($name);
    }

    /**
     * Generates form-control '<input type="text" />'.
     *
     * @param string $name
     * @return TextInput
     */
    public static function text(string $name): TextInput
    {
        return (new TextInput())->name($name);
    }

    /**
     * Generates form-control '<input type="time" />'.
     *
     * @param string $name
     * @return TimeInput
     */
    public static function time(string $name): TimeInput
    {
        return (new TimeInput())->name($name);
    }

    /**
     * Generates form-control '<input type="url" />'.
     *
     * @param string $name
     * @return UrlInput
     */
    public static function url(string $name): UrlInput
    {
        return (new UrlInput())->name($name);
    }

    /**
     * Generates form-control '<input type="week" />'.
     *
     * @param string $name
     * @return WeekInput
     */
    public static function week(string $name): WeekInput
    {
        return (new WeekInput())->name($name);
    }

    /**
     * Generates form-control '<button type="reset"></button>'.
     *
     * @param string $name
     * @return ResetButton
     */
    public static function reset(string $name = 'reset'): ResetButton
    {
        return (new ResetButton())->name($name);
    }

    /**
     * Generates form-control '<button type="submit"></button>'.
     *
     * @param string $name
     * @return SubmitButton
     */
    public static function submit(string $name = 'submit'): SubmitButton
    {
        return (new SubmitButton())->name($name);
    }

    /**
     * Generates form-control '<button></button>'.
     *
     * @param string $name
     * @return Button
     */
    public static function button(string $name = ''): Button
    {
        return (new Button())->name($name);
    }

    /**
     * Generates a DynamicList.
     *
     * @param string $arrayName : The base-array-name of all fields within this dynamic list (e.g. "users" or "users[][emails]")
     * @param DynamicListTemplateInterface $template : An element/component, that can be a DynamicListTemplate (must implement DynamicListTemplateInterface)
     * @param null $addButtonLabel : The label for the button to add a new item. (Gets auto-translated, if possible.)
     * @param null $minItems : Minimum items of this dynamic list. (Gets auto-fetched from rules, if possible.)
     * @param null $maxItems : Maximum items of this dynamic list. (Gets auto-fetched from rules, if possible.)
     * @return DynamicList
     */
    public static function dynamicList($arrayName, DynamicListTemplateInterface $template, $addButtonLabel = null, $minItems = null, $maxItems = null): DynamicList
    {
        return new DynamicList($arrayName, $template, $addButtonLabel, $minItems, $maxItems);
    }

    /**
     * Generates Panel.
     *
     * @return Panel
     */
    public static function panel(): Panel
    {
        return (new Panel());
    }

    /**
     * Generates InputGroup.
     *
     * @param array $content
     * @return InputGroup
     */
    public static function inputGroup(array $content=[]): InputGroup
    {
        return (new InputGroup())->content($content);
    }

    /**
     * Generates InputGroupButton
     *
     * @param ButtonElement $button
     * @return InputGroupButton
     */
    public static function inputGroupButton(ButtonElement $button): InputGroupButton
    {
        return new InputGroupButton($button);
    }

    /**
     * Generates Input-Group-Addon
     *
     * @param string|CheckboxInput|RadioInput $content
     * @return InputGroupAddon
     */
    public static function inputGroupAddon($content): InputGroupAddon
    {
        return new InputGroupAddon($content);
    }

    /**
     * Generates Button-Group.
     *
     * @param $buttons
     * @return ButtonGroup
     */
    public static function buttonGroup(array $buttons)
    {
        return new ButtonGroup($buttons);
    }

    /**
     * Generates form-control '<fieldset></fieldset>'.
     *
     * @param string|null $legend
     * @param null $content
     * @return FieldsetElement
     */
    public static function fieldset(string $legend = null, $content = null): FieldsetElement
    {
        $element = new FieldsetElement();
        if (!is_null($legend)) {
            $element->legend($legend);
        }
        if (!is_null($content)) {
            $element->content($content);
        }
        return $element;
    }

    /**
     * Generates an error-container for a specific field.
     *
     * @param $fieldName
     * @return ErrorContainer
     */
    public static function errorContainer(string $fieldName)
    {
        return new ErrorContainer($fieldName);
    }

    /**
     * Generates an indicator for a required field.
     *
     * @return RequiredFieldIndicator
     */
    public static function requiredFieldIndicator()
    {
        return new RequiredFieldIndicator();
    }

    /**
     * Helper-function to create an array of Option-Tag-Objects to be used as a parameter for Form::select()
     * out of a one-dimensional array of "value=>label"-pairs.
     *
     * @param array $items
     * @param bool|true $prependEmptyOption
     * @param null $defaultValue
     * @return array
     */
    public static function createOptions($items = [], $prependEmptyOption=true, $defaultValue=null) {
        $return = [];
        if ($prependEmptyOption) {
            $return[] = self::option();
        }
        foreach ($items as $value => $label) {
            $optionTag = self::option($value)->content($label);
            if ($defaultValue === $value) {
                $optionTag->selected();
            }
            $return[] = $optionTag;
        }
        return $return;
    }

    /**
     * Returns the currently open Form-element.
     *
     * @return Form
     * @throws OpenElementNotFoundException
     */
    public function getOpenForm(): Form
    {
        if (is_null($this->openForm)) {
            throw new OpenElementNotFoundException('FormFactory could not find a currently open Form-element while generating a field. This is probably due to generating a form-field with FormFactory without opening a form using Form::open() before that.');
        }

        return $this->openForm;
    }

    /**
     * Returns the currently open Select-element.
     *
     * @return Select
     * @throws OpenElementNotFoundException
     */
    public function getOpenSelect(): Select
    {
        if (is_null($this->openSelect)) {
            throw new OpenElementNotFoundException('FormFactory could not find a currently open Select-element while generating an Option-element. This is probably due to generating a option-field with FormFactory without opening a select using Form::select() before that.');
        }

        return $this->openSelect;
    }

}
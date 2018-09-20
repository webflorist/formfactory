<?php

namespace Nicat\FormFactory;

use Nicat\FormFactory\Components\Additional\ButtonGroup;
use Nicat\FormFactory\Components\Additional\ErrorContainer;
use Nicat\FormFactory\Components\Additional\RequiredFieldIndicator;
use Nicat\FormFactory\Components\HelpText\HelpTextContainer;
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
use Nicat\FormFactory\Exceptions\ElementNotFoundException;
use Nicat\FormFactory\Exceptions\OpenElementNotFoundException;
use Nicat\FormFactory\Utilities\Forms\FormInstance;
use Nicat\FormFactory\Utilities\Forms\FormManager;
use Nicat\FormFactory\Utilities\VueApp\VueAppGenerator;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\ButtonElement;
use Nicat\HtmlFactory\Elements\FieldsetElement;

/**
 * The main class of this package.
 * Provides factory methods for all FormControls
 * as well as general service-methods.
 *
 * Class FormFactory
 * @package Nicat\FormFactory
 *
 * Input-FormControls:
 * =========
 * @method static CheckboxInput         checkbox(string $name, string $value="1")
 * @method static TextInput             text(string $name)
 * @method static ColorInput            color(string $name)
 * @method static DateInput             date(string $name)
 * @method static DatetimeInput         datetime(string $name)
 * @method static DatetimeLocalInput    datetimeLocal(string $name)
 * @method static EmailInput            email(string $name)
 * @method static FileInput             file(string $name)
 * @method static HiddenInput           hidden(string $name)
 * @method static MonthInput            month(string $name)
 * @method static NumberInput           number(string $name)
 * @method static PasswordInput         password(string $name)
 * @method static RadioInput            radio(string $value, string $name = '')
 * @method static RangeInput            range(string $name)
 * @method static SearchInput           search(string $name)
 * @method static TelInput              tel(string $name)
 * @method static TimeInput             time(string $name)
 * @method static UrlInput              url(string $name)
 * @method static WeekInput             week(string $name)
 *
 * Select- and Option-FormControls:
 * =========
 * @method static Optgroup              optgroup(string $label, array $options)
 * @method static Option                option(string $value = '')
 * @method static Select                select(string $name, array $options = [])
 *
 * Button FormControls:
 * =========
 * @method static Button                button(string $name = null)
 * @method static ResetButton           reset(string $name = 'reset')
 * @method static SubmitButton          submit(string $name = 'submit')
 *
 * Misc FormControls:
 * =========
 * @method static Textarea              textarea(string $name)
 *
 */
class FormFactory
{

    /**
     * The FormManager object, that manages all created forms.
     *
     * @var FormManager
     */
    protected $forms;

    /**
     * FormFactory constructor.
     */
    public function __construct()
    {
        $this->forms = new FormManager();
    }

    /**
     * Magic method to construct a FormControl.
     * See '@method' declarations of class-phpdoc
     * for available methods.
     *
     * @param $accessor
     * @param $arguments
     * @return Element
     *
     * @throws ElementNotFoundException
     * @throws OpenElementNotFoundException
     */
    public function __call($accessor, $arguments)
    {

        $formControlClass = $this->getFormControlClassNameForAccessor($accessor);

        if (class_exists($formControlClass)) {
            $formControlElement = new $formControlClass(...$arguments);
            if ($this->forms->hasOpenForm()) {
                $this->getOpenForm()->addFormControl($formControlElement);
            }
            return $formControlElement;
        }

        // If the accessor is neither a element nor a component, we throw an exception.
        throw new ElementNotFoundException('No FormControl found for accessor "'.$accessor.'".');

    }

    /**
     * Returns the FormFactory singleton from Laravel's Service Container.
     *
     * @return FormFactory
     */
    public static function singleton(): FormFactory
    {
        return app(FormFactory::class);
    }

    /**
     * Generates and returns the opening form-tag.
     * Also adds the form to $this->forms.
     *
     * @param string $id
     * @return Form
     * @throws \Nicat\HtmlFactory\Exceptions\AttributeNotAllowedException
     * @throws \Nicat\HtmlFactory\Exceptions\AttributeNotFoundException
     */
    public static function open(string $id): Form
    {
        $form = (new Form())->id($id)->method('post');
        FormFactory::singleton()->forms->addForm(
            new FormInstance($form)
        );
        return $form;
    }

    /**
     * Creates the closing-tag of the form
     *
     * @param bool $showRequiredFieldsLegend
     * @return string
     * @throws OpenElementNotFoundException
     */
    public static function close(bool $showRequiredFieldsLegend = true)
    {
        $return = '';
        if ($showRequiredFieldsLegend && FormFactory::singleton()->getOpenForm()->wasRequiredFieldIndicatorUsed()) {
            $return .= new RequiredFieldsLegend();
        }
        $return .= '</form>';
        FormFactory::singleton()->getOpenForm()->closeForm();
        return $return;
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
     * Generates an empty HelpText-container.
     *
     * @return HelpTextContainer
     */
    public static function helpTextContainer()
    {
        return new HelpTextContainer();
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
            $return[] = self::singleton()->option();
        }
        foreach ($items as $value => $label) {
            $optionTag = self::singleton()->option($value)->content($label);
            if ($defaultValue === $value) {
                $optionTag->selected();
            }
            $return[] = $optionTag;
        }
        return $return;
    }

    /**
     * Returns the currently open FormInstance-element.
     *
     * @return FormInstance
     * @throws OpenElementNotFoundException
     */
    public function getOpenForm(): FormInstance
    {
        return $this->forms->getOpenForm();
    }

    /**
     * Returns the FormInstance with the specified id.
     *
     * @param string $id
     * @return FormInstance
     * @throws Exceptions\FormInstanceNotFoundException
     */
    public function getForm(string $id): FormInstance
    {
        return $this->forms->getForm($id);
    }

    /**
     * Returns FQCN for a FormControl by it's accessor.
     *
     * @param $accessor
     * @return string
     */
    private function getFormControlClassNameForAccessor(string $accessor) : string
    {
        $shortClassName = ucfirst($accessor);
        $buttonAccessors = [
            'submit',
            'reset'
        ];
        $inputAccessors = [
            'checkbox',
            'color',
            'date',
            'datetime',
            'datetimeLocal',
            'email',
            'file',
            'hidden',
            'month',
            'number',
            'password',
            'radio',
            'range',
            'search',
            'tel',
            'text',
            'time',
            'url',
            'week'
        ];
        if (array_search($accessor,$buttonAccessors) !== false) {
            $shortClassName .= 'Button';
        }
        if (array_search($accessor,$inputAccessors) !== false) {
            $shortClassName .= 'Input';
        }

        return 'Nicat\\FormFactory\\Components\\FormControls\\'.$shortClassName;
    }

    /**
     * Generates a vue-app for an already closed form..
     *
     * @param string $id
     * @return string
     * @throws Exceptions\FormInstanceNotFoundException
     */
    public static function generateVueApp(string $id): string
    {
        return (new VueAppGenerator(FormFactory::singleton()->forms->getForm($id)))->generate();
    }

}
<?php

namespace Nicat\FormFactory;

use Nicat\FormFactory\Components\FormControls\ButtonGroup;
use Nicat\FormFactory\Components\Helpers\ErrorContainer;
use Nicat\FormFactory\Components\DynamicLists\DynamicList;
use Nicat\FormFactory\Components\FormControls\Button;
use Nicat\FormFactory\Components\FormControls\CheckboxGroup;
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
use Nicat\FormFactory\Components\FormControls\InputGroup;
use Nicat\FormFactory\Components\FormControls\MonthInput;
use Nicat\FormFactory\Components\FormControls\NumberInput;
use Nicat\FormFactory\Components\FormControls\Optgroup;
use Nicat\FormFactory\Components\FormControls\Option;
use Nicat\FormFactory\Components\FormControls\PasswordInput;
use Nicat\FormFactory\Components\FormControls\RadioGroup;
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
use Nicat\FormFactory\Utilities\Forms\FormInstanceManager;
use Nicat\FormFactory\Utilities\VueApp\VueAppGenerator;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\FieldsetElement;
use Nicat\VueFactory\VueInstance;

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
 * @method static RadioGroup            radioGroup(string $name, array $radioInputs)
 * @method static InputGroup            inputGroup(array $content)
 * @method static CheckboxGroup         checkboxGroup(array $radioInputs)
 * @method static ButtonGroup           buttonGroup(array $buttons)
 *
 */
class FormFactory
{

    /**
     * The FormInstanceManager, that manages all created FormInstances.
     *
     * @var FormInstanceManager
     */
    protected $formInstances;

    /**
     * FormFactory constructor.
     */
    public function __construct()
    {
        $this->formInstances = new FormInstanceManager();
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
     * Magic method to construct a FormControl.
     * See '@method' declarations of class-phpdoc
     * for available methods.
     *
     * @param $accessor
     * @param $arguments
     * @return Element
     *
     * @throws ElementNotFoundException
     */
    public function __call($accessor, $arguments)
    {

        $formControlClass = $this->getFormControlClassNameForAccessor($accessor);

        if (class_exists($formControlClass)) {
            return new $formControlClass(...$arguments);
        }

        throw new ElementNotFoundException('No FormControl found for accessor "'.$accessor.'".');

    }

    /**
     * Generates and returns the opening form-tag.
     * Also creates a new FormInstance and adds it to $this->formInstances.
     *
     * @param string $id
     * @return Form
     */
    public static function open(string $id): Form
    {
        $form = (new Form())->id($id)->method('post');
        FormFactory::singleton()->formInstances->addForm(
            new FormInstance($form)
        );
        return $form;
    }

    /**
     * Creates the closing-tag of the form
     *
     * @return string
     * @throws OpenElementNotFoundException
     */
    public static function close()
    {
        FormFactory::singleton()->getOpenForm()->closeForm();
        return '</form>';
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
     * Creates an error-container for a certain field-name.
     *
     * @param $fieldName
     * @return ErrorContainer
     */
    public static function errorContainer($fieldName) : ErrorContainer
    {
        return new ErrorContainer($fieldName);
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
        return $this->formInstances->getOpenForm();
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
        return $this->formInstances->getForm($id);
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
     * Generates a Vue instance for the form with ID $id.
     *
     * @param string $id
     * @return VueInstance
     * @throws Exceptions\FormInstanceNotFoundException
     */
    public static function vue(string $id): VueInstance
    {
        return (new VueAppGenerator(FormFactory::singleton()->formInstances->getForm($id)))->getVueInstance();
    }
}
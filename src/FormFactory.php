<?php

namespace Nicat\FormFactory;

use Nicat\FormFactory\Components\Helpers\RequiredFieldsLegend;
use Nicat\FormFactory\Components\FormControls\ButtonGroup;
use Nicat\FormFactory\Components\Helpers\ErrorContainer;
use Nicat\FormFactory\Components\FormControls\Button;
use Nicat\FormFactory\Components\FormControls\CheckboxGroup;
use Nicat\FormFactory\Components\FormControls\CheckboxInput;
use Nicat\FormFactory\Components\FormControls\ColorInput;
use Nicat\FormFactory\Components\FormControls\DateInput;
use Nicat\FormFactory\Components\FormControls\DatetimeInput;
use Nicat\FormFactory\Components\FormControls\DatetimeLocalInput;
use Nicat\FormFactory\Components\FormControls\EmailInput;
use Nicat\FormFactory\Components\FormControls\FileInput;
use Nicat\FormFactory\Components\Form\Form;
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
use Nicat\FormFactory\Exceptions\FormNotFoundException;
use Nicat\FormFactory\Exceptions\MissingVueDependencyException;
use Nicat\FormFactory\Exceptions\OpenElementNotFoundException;
use Nicat\FormFactory\Exceptions\VueAppAlreadyGeneratedException;
use Nicat\FormFactory\Utilities\FormManager;
use Nicat\FormFactory\Vue\VueAppGenerator;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
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
     * The FormManager, that manages all created Forms.
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
     * Also creates a new Form and adds it to $this->formInstances.
     *
     * @param string $id
     * @return Form
     */
    public static function open(string $id): Form
    {
        $form = (new Form($id));
        FormFactory::singleton()->forms->addForm($form);
        return $form;
    }

    /**
     * Creates the closing-tag of the form
     *
     * @param bool $appendRequiredFieldsLegend
     * @return string
     */
    public static function close($appendRequiredFieldsLegend=true)
    {
        $return = '';
        $openForm = null;

        try {
            $openForm = FormFactory::singleton()->getOpenForm();
            $appendRequiredFieldsLegend = $openForm->wasRequiredFieldIndicatorUsed();
        } catch (OpenElementNotFoundException $e) {
        }

        if ($appendRequiredFieldsLegend) {
            $return .= new RequiredFieldsLegend();
        }

        $return .= '</form>';

        return $return;
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
     * Returns the currently open Form-element.
     *
     * @return Form
     * @throws OpenElementNotFoundException
     */
    public function getOpenForm(): Form
    {
        return $this->forms->getOpenForm();
    }

    /**
     * Returns the Form with the specified id.
     *
     * @param string $id
     * @return Form
     * @throws Exceptions\FormNotFoundException
     */
    public function getForm(string $id): Form
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
     * Generates a Vue instance for the form with ID $id.
     *
     * @param string $id
     * @return VueInstance
     * @throws FormNotFoundException
     * @throws MissingVueDependencyException
     */
    public static function vue(string $id): VueInstance
    {
        $form = FormFactory::singleton()->forms->getForm($id);
        if (!$form->isVueEnabled()) {
            throw new MissingVueDependencyException("Cannot generate vue-app for form with '$id', since it is not enabled for vue.");
        }

        $form->vueAppGenerated = true;
        return (new VueAppGenerator($form))->getVueInstance();
    }

    /**
     * Generates JavaScript for all vue-enabled forms,
     * that weren't generated before.
     *
     * Call this function at the end of your master-template
     * to ensure the generation of all required vue-apps.
     *
     * @return string
     */
    public static function generateVueApps(): string
    {

        $vueApps = '';

        foreach (FormFactory::singleton()->forms->getForms() as $form) {
            if ($form->isVueEnabled() && !$form->vueAppGenerated) {
                $vueApps .= (new VueAppGenerator($form))->getVueInstance()->generate();
                $form->vueAppGenerated = true;
            }
        }

        return $vueApps;
    }
}
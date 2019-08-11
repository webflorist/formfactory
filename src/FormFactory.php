<?php

namespace Webflorist\FormFactory;

use Webflorist\FormFactory\Components\Form\VueForm;
use Webflorist\FormFactory\Components\Helpers\RequiredFieldsLegend;
use Webflorist\FormFactory\Components\FormControls\ButtonGroup;
use Webflorist\FormFactory\Components\Helpers\ErrorContainer;
use Webflorist\FormFactory\Components\FormControls\Button;
use Webflorist\FormFactory\Components\FormControls\CheckboxGroup;
use Webflorist\FormFactory\Components\FormControls\CheckboxInput;
use Webflorist\FormFactory\Components\FormControls\ColorInput;
use Webflorist\FormFactory\Components\FormControls\DateInput;
use Webflorist\FormFactory\Components\FormControls\DatetimeInput;
use Webflorist\FormFactory\Components\FormControls\DatetimeLocalInput;
use Webflorist\FormFactory\Components\FormControls\EmailInput;
use Webflorist\FormFactory\Components\FormControls\FileInput;
use Webflorist\FormFactory\Components\Form\Form;
use Webflorist\FormFactory\Components\FormControls\HiddenInput;
use Webflorist\FormFactory\Components\FormControls\InputGroup;
use Webflorist\FormFactory\Components\FormControls\MonthInput;
use Webflorist\FormFactory\Components\FormControls\NumberInput;
use Webflorist\FormFactory\Components\FormControls\Optgroup;
use Webflorist\FormFactory\Components\FormControls\Option;
use Webflorist\FormFactory\Components\FormControls\PasswordInput;
use Webflorist\FormFactory\Components\FormControls\RadioGroup;
use Webflorist\FormFactory\Components\FormControls\RadioInput;
use Webflorist\FormFactory\Components\FormControls\RangeInput;
use Webflorist\FormFactory\Components\FormControls\ResetButton;
use Webflorist\FormFactory\Components\FormControls\SearchInput;
use Webflorist\FormFactory\Components\FormControls\Select;
use Webflorist\FormFactory\Components\FormControls\SubmitButton;
use Webflorist\FormFactory\Components\FormControls\TelInput;
use Webflorist\FormFactory\Components\FormControls\Textarea;
use Webflorist\FormFactory\Components\FormControls\TextInput;
use Webflorist\FormFactory\Components\FormControls\TimeInput;
use Webflorist\FormFactory\Components\FormControls\UrlInput;
use Webflorist\FormFactory\Components\FormControls\WeekInput;
use Webflorist\FormFactory\Exceptions\ElementNotFoundException;
use Webflorist\FormFactory\Exceptions\FormNotFoundException;
use Webflorist\FormFactory\Exceptions\MissingVueDependencyException;
use Webflorist\FormFactory\Exceptions\OpenElementNotFoundException;
use Webflorist\FormFactory\Utilities\FormManager;
use Webflorist\FormFactory\Vue\VueInstanceGenerator;
use Webflorist\HtmlFactory\Elements\Abstracts\Element;
use Webflorist\VueFactory\VueInstance;

/**
 * The main class of this package.
 * Provides factory methods for all FormControls
 * as well as general service-methods.
 *
 * Class FormFactory
 * @package Webflorist\FormFactory
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
     * Creates and returns a new form.
     * Renders the form-start-tag on generation.
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
     * Creates and returns a new vue-powered form.
     * Renders the form-start-tag on generation.
     *
     * @param string $id
     * @return VueForm|Form
     */
    public static function vOpen(string $id)
    {
        if (!config('formfactory.vue.enabled')) {
            return (self::open($id));
        }

        $form = (new VueForm($id));
        FormFactory::singleton()->forms->addForm($form);
        return $form;
    }

    /**
     * Creates the closing-tag of the form
     *
     * @param bool $appendRequiredFieldsLegend
     * @return string
     * @throws OpenElementNotFoundException
     */
    public static function close($appendRequiredFieldsLegend=true)
    {
        $return = '';
        $openForm = FormFactory::singleton()->getOpenForm();

        // For non-VueForms we add an ErrorContainer containing all unclaimed errors.
        if (!$openForm->is(VueForm::class) && $openForm->errors->hasUnclaimedErrors()) {
            foreach($openForm->errors->getUnclaimedErrors() as $errorFieldName => $errors) {
                $return .= (new ErrorContainer($errorFieldName));
            }
        }

        if ($appendRequiredFieldsLegend && $openForm->wasRequiredFieldIndicatorUsed()) {
            $return .= new RequiredFieldsLegend();
        }

        $return .= '</form>';

        return $return;
    }

    /**
     * Creates the required fields legend.
     *
     * @param bool $appendRequiredFieldsLegend
     * @return string
     * @throws OpenElementNotFoundException
     */
    public static function requiredFieldsLegend()
    {
        return new RequiredFieldsLegend();
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

        return 'Webflorist\\FormFactory\\Components\\FormControls\\'.$shortClassName;
    }

    /**
     * Generates a VueInstance for the form with ID $id.
     *
     * @param string $id
     * @return VueInstance
     * @throws FormNotFoundException
     * @throws MissingVueDependencyException
     */
    public static function vueInstance(string $id): VueInstance
    {
        $form = FormFactory::singleton()->forms->getForm($id);
        if (!$form->is(VueForm::class)) {
            throw new MissingVueDependencyException("Cannot generate vue instance for form with '$id', since it is not a VueForm. Use Form::vOpen() instead of Form::open().");
        }
        /** @var VueForm $form */
        return $form->getVueInstance();
    }

    /**
     * Generates vue instances for all VueForms,
     * that weren't generated before.
     *
     * Call this function at the end of your master-template
     * to ensure the generation of all required vue instances.
     *
     * @return string
     */
    public static function generateVueInstances(): string
    {

        $vueInstances = '';

        foreach (FormFactory::singleton()->forms->getForms() as $form) {
            if ($form->is(VueForm::class)) {
                /** @var VueForm $form */
                $vueInstance = $form->getVueInstance();
                if (!is_null($vueInstance)) {
                    $vueInstances .= $vueInstance->generate();
                }
            }
        }

        return $vueInstances;
    }
}

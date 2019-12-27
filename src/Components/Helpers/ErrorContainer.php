<?php

namespace Webflorist\FormFactory\Components\Helpers;

use Webflorist\FormFactory\Components\FormControls\Contracts\FieldInterface;
use Webflorist\FormFactory\Components\FormControls\Contracts\FormControlInterface;
use Webflorist\FormFactory\Components\FormControls\TempField;
use Webflorist\FormFactory\Components\FormControls\TextInput;
use Webflorist\FormFactory\Utilities\FormFactoryTools;
use Webflorist\HtmlFactory\Components\AlertComponent;
use Webflorist\HtmlFactory\Elements\Abstracts\Element;
use Webflorist\HtmlFactory\Elements\DivElement;

class ErrorContainer extends DivElement
{

    /**
     * The field these ErrorContainer belongs to.
     *
     * @var null|FieldInterface|FormControlInterface|Element
     */
    public $field;

    /**
     * Additional field-names this ErrorContainer should display errors for.
     *
     * @var string
     */
    public $additionalErrorFields = [];

    /**
     * Should errors be displayed?
     *
     * @var bool
     */
    public $displayErrors = true;

    /**
     * Should aria-invalid and aria-describedby attributes be applied to the field?
     *
     * @var bool
     */
    public $applyAriaAttributes = true;

    /**
     * Array of error-messages to display.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * ErrorContainer constructor.
     *
     * @param FieldInterface|string|null $field
     */
    public function __construct($field = null)
    {
        parent::__construct();

        // If we just get a field-name, we create a temporary text-input from it,
        // since a FieldInterface is required for further processing.
        if (is_string($field)) {
            $field = (new TempField($field));
        }

        $this->field = $field;

        if (!is_null($this->field)) {
            $this->id(function () {
                $containerId = $this->field->getFieldName() . '_errors';
                if ($this->field->belongsToForm()) {
                    $containerId = $this->field->getForm()->getId() . '_' . $containerId;
                }
                return $containerId;
            });
        }
    }

    public function addAdditionalErrorField(string $fieldName)
    {
        $this->additionalErrorFields[] = $fieldName;
    }

    /**
     * Sets the errors to display.
     *
     * @param array $errors
     * @return ErrorContainer
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * Adds errors to display.
     *
     * @param array $errors
     * @return ErrorContainer
     */
    public function addErrors(array $errors)
    {
        if (is_null($this->errors)) {
            $this->errors = [];
        }
        $this->errors = array_merge($this->errors, $errors);
        return $this;
    }

    /**
     * Returns the array of error-messages.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Are any errors set?
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !is_null($this->errors) && count($this->errors) > 0;
    }

    /**
     * Do not display errors.
     *
     * By default also turns off application of aria-attributes.
     *
     * @param bool $applyAriaAttributes
     */
    public function hideErrors($applyAriaAttributes = false)
    {
        $this->displayErrors = false;
        $this->applyAriaAttributes = $applyAriaAttributes;
    }

    /**
     * Gets called before applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function beforeDecoration()
    {
        if (!is_null($this->field)) {

            if ($this->applyAriaAttributes) {
                $this->applyAriaAttributes();
            }

            if ($this->field->isVueEnabled()) {
                $vIf = [];
                foreach ($this->getErrorFields() as $fieldName) {
                    $this->appendContent(
                        (new DivElement())->vFor("error in errors['$fieldName']")->content('{{ error }}')
                    );
                    $vIf[] = "fieldHasError('$fieldName')";
                }
                $this->vIf(implode(' || ', $vIf));
            } else {
                $this->getErrorsFromFormInstance();
                foreach ($this->getErrors() as $error) {
                    $this->appendContent((new DivElement())->content($error));
                }
            }

        }
    }

    /**
     * Don't render output, if errors should not be displayed.
     * Wrap output in template-tags, if vue is enabled.
     *
     * @param string $output
     */
    protected function manipulateOutput(string &$output)
    {

        if (!is_null($this->field)) {

            if ($this->field->isVueEnabled()) {
                $output = "<template>$output</template>";
            }

            if (!$this->field->isVueEnabled() && !$this->hasErrors()) {
                $output = '';
            }

        }

        if (!$this->displayErrors) {
            $output = '';
        }
    }

    /**
     * Fetches errors from the Form this Field belongs to.
     */
    private function getErrorsFromFormInstance()
    {
        if ($this->field->belongsToForm()) {
            $fieldErrorManager = $this->field->getForm()->errors;
            foreach ($this->getErrorFields() as $fieldName) {
                if ($fieldErrorManager->hasErrorsForField($fieldName)) {
                    $this->addErrors($fieldErrorManager->getErrorsForField($fieldName));
                }
            }
        }
    }

    /**
     * Adds the aria-invalid and aria-describedby-attributes to the Field.
     */
    private function applyAriaAttributes()
    {

        // We do not set the aria-attributes, if vue is used,
        // since this will be reactive.
        if (!$this->field->isVueEnabled()) {

            $this->field->addAriaDescribedby(function () {
                if ($this->hasErrors()) {
                    return $this->attributes->id;
                }
                return null;
            });

            $this->field->ariaInvalid(function () {
                if ($this->hasErrors()) {
                    return 'true';
                }
                return null;
            });
        }
    }

    /**
     * Returns all field-names this ErrorContainer should display errors for.
     *
     * @return array
     */
    protected function getErrorFields(): array
    {
        if (!is_null($this->field)) {
            return array_merge([$this->field->getFieldName()], $this->additionalErrorFields);
        }
        return $this->additionalErrorFields;
    }

}
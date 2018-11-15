<?php

namespace Webflorist\FormFactory\Components\Helpers;

use Webflorist\FormFactory\Components\FormControls\Contracts\FieldInterface;
use Webflorist\FormFactory\Components\FormControls\Contracts\FormControlInterface;
use Webflorist\FormFactory\Components\FormControls\TextInput;
use Webflorist\HtmlFactory\Components\AlertComponent;
use Webflorist\HtmlFactory\Elements\Abstracts\Element;
use Webflorist\HtmlFactory\Elements\DivElement;

class ErrorContainer extends AlertComponent
{

    /**
     * The field these ErrorContainer belongs to.
     *
     * @var null|FieldInterface|FormControlInterface|Element
     */
    public $field;

    /**
     * The field-name this ErrorContainer should display errors for.
     *
     * @var FieldInterface|FormControlInterface|Element
     */
    public $fieldName;

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
        parent::__construct('danger');

        // If we just get a field-name, we create a temporary text-input from it,
        // since a FieldInterface is required for further processing.
        if (is_string($field)) {
            $field = (new TextInput($field));
        }

        $this->field = $field;

        if (!is_null($this->field)) {

            $this->getErrorsFromFormInstance();

            $this->id(function () {
                $containerId = $this->field->getFieldName() . '_errors';
                if ($this->field->belongsToForm()) {
                    $containerId = $this->field->getForm()->getId() . '_' . $containerId;
                }
                return $containerId;
            });

        }
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
        return count($this->errors) > 0;
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
                $fieldName = $this->field->getFieldName();
                $this->appendContent(
                    (new DivElement())->vFor("error in fields['$fieldName'].errors")->content('{{ error }}')
                );
                $this->vIf("fieldHasError('$fieldName')");
            } else {
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
            $formInstanceErrors = $this->field->getForm()->errors;
            $fieldName = $this->field->getFieldName();
            if ($formInstanceErrors->hasErrorsForField($fieldName)) {
                $this->setErrors($formInstanceErrors->getErrorsForField($fieldName));
            }
        }
    }

    /**
     * Adds the aria-invalid and aria-describedby-attributes to the Field.
     */
    private function applyAriaAttributes()
    {
        $this->field->addAriaDescribedby(function () {

            // If vue is enabled, we always set the aria-describedby attribute.
            // If not, we only set it, if there actually are errors.
            if ($this->hasErrors() || $this->field->isVueEnabled()) {
                return $this->attributes->id;
            }

            return null;
        });

        // We do not set the aria-invalid attribute, if vue is used,
        // since this will be reactive.
        if (!$this->field->isVueEnabled()) {
            $this->field->ariaInvalid(function () {
                if ($this->hasErrors()) {
                    return 'true';
                }
                return null;
            });
        }
    }


}
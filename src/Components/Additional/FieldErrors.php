<?php

namespace Nicat\FormFactory\Components\Additional;

use Nicat\FormFactory\Components\Contracts\FieldInterface;
use Nicat\FormFactory\Components\Contracts\FormControlInterface;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\HtmlFactory\Components\AlertComponent;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\DivElement;
use Nicat\HtmlFactory\Elements\TemplateElement;

class FieldErrors extends AlertComponent
{

    /**
     * The field these FieldErrors belongs to.
     *
     * @var FieldInterface|FormControlInterface|Element
     */
    public $field;

    /**
     * Should errors be displayed?
     *
     * @var bool
     */
    public $displayErrors = true;

    /**
     * Array of error-messages to display.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * FieldErrors constructor.
     *
     * @param FieldInterface $field
     */
    public function __construct(FieldInterface $field)
    {
        parent::__construct('danger');
        $this->field = $field;
        $this->getErrorsFromFormInstance();

        $this->id(function () {
            $containerId = $this->field->getFieldName() . '_errors';
            if ($this->field->hasFormInstance()) {
                $containerId = $this->field->getFormInstance()->getId() . '_' . $containerId;
            }
            return $containerId;
        });
    }

    /**
     * Sets the errors to display.
     *
     * @param array $errors
     * @return FieldErrors
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
    public function hasErrors() : bool
    {
        return count($this->errors) > 0;
    }

    /**
     * Do not display errors.
     */
    public function hideErrors()
    {
        $this->displayErrors = false;
    }

    /**
     * Gets called before applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function beforeDecoration()
    {
        $this->addAriaTags();

        if ($this->field->isVueEnabled()) {
            $fieldName = $this->field->getFieldName();
            $this->appendContent(
                (new DivElement())->vFor("error in fields['$fieldName'].errors")->content('{{ error }}')
            );
            $this->vIf("fieldHasError('$fieldName')");
        }
        else {
            foreach ($this->getErrors() as $error) {
                $this->appendContent((new DivElement())->content($error));
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

        if ($this->field->isVueEnabled()) {
            $output = "<template>$output</template>";
        }

        if (!$this->displayErrors) {
            $output = '';
        }

        if (!$this->field->isVueEnabled() && !$this->hasErrors()) {
            $output = '';
        }
    }

    /**
     * Fetches errors from the FormInstance this Field belongs to.
     */
    private function getErrorsFromFormInstance()
    {
        if ($this->field->hasFormInstance()) {
            $formInstanceErrors = $this->field->getFormInstance()->errors;
            $fieldName = $this->field->getFieldName();
            if ($formInstanceErrors->hasErrorsForField($fieldName)) {
                $this->setErrors($formInstanceErrors->getErrorsForField($fieldName));
            }
        }
    }

    /**
     * Adds the aria-invalid and aria-describedby-attributes to the Field.
     */
    private function addAriaTags()
    {
        $this->field->addAriaDescribedby(function () {

            // If vue is enabled, we always set the aria-describedby attribute.
            // If not, we only set it, if there actually are errors.
            if ($this->displayErrors && ($this->hasErrors() || $this->field->isVueEnabled())) {
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
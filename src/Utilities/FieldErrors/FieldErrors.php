<?php

namespace Nicat\FormFactory\Utilities\FieldErrors;

use Nicat\FormFactory\Components\Contracts\FieldInterface;
use Nicat\FormFactory\Components\Contracts\FormControlInterface;

class FieldErrors
{

    /**
     * The field these FieldErrors belongs to.
     *
     * @var FieldInterface|FormControlInterface
     */
    protected $field;

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
        $this->field = $field;
        $this->getErrorsFromFormInstance();
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
     * Returns the proposed ID for the error-container.
     * This will also be used for the aria-describedby attribute.
     */
    public function getContainerId()
    {
        $containerId = $this->field->getFieldName() . '_errors';
        if ($this->field->hasFormInstance()) {
            $containerId = $this->field->getFormInstance()->getId() . '_' . $containerId;
        }
        return $containerId;
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


}
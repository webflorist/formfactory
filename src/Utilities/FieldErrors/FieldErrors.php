<?php

namespace Nicat\FormFactory\Utilities\FieldErrors;

use Nicat\HtmlFactory\Elements\Abstracts\Element;

class FieldErrors
{

    /**
     * The field these FieldErrors belongs to.
     *
     * @var Element
     */
    protected $field;

    /**
     * Array of error-messages to display.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * FieldErrors constructor.
     *
     * @param Element|null $field
     */
    public function __construct($field = null)
    {
        $this->field = $field;
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


}
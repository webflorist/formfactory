<?php

namespace Nicat\FormBuilder\Elements\Traits;

use Nicat\FormBuilder\FormBuilder;


trait CanHaveErrors
{

    /**
     * Array of errors for this field.
     *
     * @var null|array
     */
    protected $errors = null;

    /**
     * Whether errors of this field should be shown at all.
     *
     * @var null|bool
     */
    public $showErrors = true;

    /**
     * Array of fieldname(s), whose errors should be displayed for this tag.
     * This is only used, if this tag should display errors for other fields than itself.
     *
     * @var array
     */
    protected $errorFields = [];

    /**
     * Where the errors should be displayed.
     *
     * @var string: before|after|append|prepend
     */
    public $errorsLocation = 'before';

    /**
     * Set array of errors for this tag.
     * (omit for automatic adoption from session)
     *
     * @param array $errors
     * @return $this
     */
    public function errors(array $errors = [])
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * Set if errors should be displayed for this field.
     *
     * @param bool|false $showErrors
     * @return $this
     */
    public function showErrors(bool $showErrors)
    {
        $this->showErrors = $showErrors;
        return $this;
    }

    /**
     * Get array of errors for this field.
     *
     * @return array
     */
    public function getErrors() : array
    {
        // If no errors were specifically set using the 'errors' method of this field,
        // we try to fill them via the FormBuilder service.
        if (is_null($this->errors)) {
            /** @var FormBuilder $formBuilderService */
            $formBuilderService = app(FormBuilder::class);
            $this->errors =  $formBuilderService->openForm->getErrorsForField($this->attributes->name);

            if (count($this->errorFields) > 0) {
                foreach ($this->errorFields as $errorField) {
                    $this->errors = array_merge($this->errors, $formBuilderService->openForm->getErrorsForField($errorField));
                }
            }

        }

        return $this->errors;
    }

    /**
     * Does this field have errors?
     *
     * @return bool
     */
    public function hasErrors()
    {
        return count($this->getErrors()) > 0;
    }

}
<?php

namespace Nicat\FormFactory\Components\Additional;

use Nicat\FormFactory\FormFactory;
use Nicat\HtmlFactory\Components\AlertComponent;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\DivElement;

class ErrorContainer extends AlertComponent
{

    /**
     * The field this ErrorContainer belongs to.
     *
     * @var Element
     */
    public $field;

    /**
     * Array of errors to display in this ErrorContainer.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * ErrorContainer constructor.
     *
     * @param Element|null $field
     */
    public function __construct($field = null)
    {
        parent::__construct('danger');

        $this->field = $field;

        $this->data('error-container', true);

        $this->id($this->field->attributes->id . '_errors');
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
     * Are any errors set?
     *
     * @return bool
     */
    public function hasErrors() : bool
    {
        return count($this->errors) > 0;
    }


    /**
     * Gets called before applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function afterDecoration()
    {
        // Establish id of this error-container.
        $this->establishId();

        $this->addErrors();

        // If this error-container has nothing to display, we simply hide it.
        if (!$this->content->hasContent()) {
            $this->hidden();
            $this->addStyle('display:none');
        }
    }

    /**
     * Establishes the ID for this error-container.
     */
    private function establishId()
    {
        // If the errorContainer should only display errors for a single field-element,
        // we use the field's ID plus the suffix '_errors' as this errorContainer's ID.
        if ((count($this->errorFieldNames) === 0) && count($this->errorFieldElements) == 1) {
            $this->id($this->errorFieldElements[0]->attributes->id . '_errors');
            return;
        }

        // Otherwise we create a md5-hash of all field-id's and fieldNames this errorContainer should display errors for,
        // append the suffix '_errors' and use it as this errorContainer's ID.
        $fieldIDs = '';
        foreach ($this->errorFieldElements as $fieldElement) {
            $fieldIDs .= $fieldElement->attributes->id;
        }
        foreach ($this->errorFieldNames as $fieldName) {
            $fieldIDs .= $fieldName;
        }
        $this->id(md5($fieldIDs) . '_errors');

    }

    /**
     * Adds all errors for all fields within $this->errorFieldElements and $this->errorFieldNames.
     *
     * @throws \Nicat\FormFactory\Exceptions\OpenElementNotFoundException
     */
    private function addErrors()
    {

        $displaysErrorsFor = $this->errorFieldNames;

        // For stated fieldElements, we ask themselves for errors and add aria-attributes.
        foreach ($this->errorFieldElements as $field) {

            if ($field->showErrors) {

                // In case the field has indeed errors.
                if ($field->hasErrors()) {

                    $this->addErrorMessages($field->getErrors());

                    // Add the 'aria-invalid' attribute to the field-element.
                    $field->ariaInvalid();

                }

                // Add the 'aria-describedby' attribute to the field-element.
                $field->addAriaDescribedby($this->attributes->id);

                $displaysErrorsFor[] = $field->attributes->name;
            }

        }

        // For stated fieldNames, we can only try to get errors from the $formFactory-service.
        foreach ($this->errorFieldNames as $fieldName) {
            /** @var FormFactory $formFactory */
            $formFactory = FormFactory::singleton();
            $this->addErrorMessages($formFactory->getOpenForm()->errors->getErrorsForField($fieldName));
        }

        $this->data('displays-errors-for', implode('|', $displaysErrorsFor));
    }

    /**
     * Adds the all $errorMessages to this errorContainer's content.
     *
     * @param string[] $errorMessages
     */
    private function addErrorMessages(array $errorMessages)
    {
        foreach ($errorMessages as $errorMsg) {
            $this->content((new DivElement())->content($errorMsg));
        }
    }


}
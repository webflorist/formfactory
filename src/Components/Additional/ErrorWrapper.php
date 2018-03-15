<?php

namespace Nicat\FormBuilder\Components\Additional;

use Nicat\FormBuilder\FormBuilder;
use Nicat\HtmlBuilder\Components\AlertComponent;
use Nicat\HtmlBuilder\Elements\Abstracts\Element;
use Nicat\HtmlBuilder\Elements\DivElement;

class ErrorWrapper extends AlertComponent
{

    /**
     * List of field-elements, this tag should display errors for.
     *
     * @var Element[]
     */
    public $errorFieldElements = [];

    /**
     * List of additional field-names, this tag should display errors for.
     *
     * @var Element[]|string[]
     */
    public $errorFieldNames = [];

    /**
     * ErrorWrapper constructor.
     */
    public function __construct()
    {
        parent::__construct('danger');
        $this->data('error-wrapper',true);
    }

    /**
     * Adds an element to the list of fields, this tag should display errors for.
     *
     * $field can either be an Element (which must use the 'CanHaveErrors' trait),
     * or simply a field-name.
     *
     * @param Element|string $field
     */
    public function addErrorField($field)
    {
        if (is_a($field, Element::class)) {
            if (method_exists($field, 'errors') && array_search($field, $this->errorFieldElements) === false) {
                $this->errorFieldElements[] = $field;
            }
        }

        if (is_string($field)) {
            $this->errorFieldNames[] = $field;
        }
    }

    /**
     * Gets called before applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function afterDecoration()
    {
        // Establish id of this error-wrapper.
        $this->establishId();

        $this->addErrors();

        // If this error-wrapper has nothing to display, we simply hide it.
        if (!$this->content->hasContent()) {
            $this->hidden();
            $this->addStyle('display:none');
        }
    }

    /**
     * Establishes the ID for this error-wrapper.
     */
    private function establishId()
    {
        // If the errorWrapper should only display errors for a single field-element,
        // we use the field's ID plus the suffix '_errors' as this errorWrapper's ID.
        if ((count($this->errorFieldNames) === 0) && count($this->errorFieldElements) == 1) {
            $this->id($this->errorFieldElements[0]->attributes->id . '_errors');
            return;
        }

        // Otherwise we create a md5-hash of all field-id's and fieldNames this errorWrapper should display errors for,
        // append the suffix '_errors' and use it as this errorWrapper's ID.
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

        // For stated fieldNames, we can only try to get errors from the $formBuilder-service.
        foreach ($this->errorFieldNames as $fieldName) {
            /** @var FormBuilder $formBuilder */
            $formBuilder = app(FormBuilder::class);
            $this->addErrorMessages($formBuilder->openForm->errors->getErrorsForField($fieldName));
        }

        $this->data('displays-errors-for',implode('|',$displaysErrorsFor));
    }

    /**
     * Adds the all $errorMessages to this errorWrapper's content.
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
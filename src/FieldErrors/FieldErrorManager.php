<?php

namespace Nicat\FormBuilder\FieldErrors;

use Nicat\FormBuilder\Elements\FormElement;
use Nicat\FormBuilder\FormBuilder;
use Nicat\FormBuilder\FormBuilderTools;
use Nicat\HtmlBuilder\Elements\Abstracts\Element;

/**
 * Manages field-errors for forms.
 *
 * Class FieldErrorManager
 * @package Nicat\FormBuilder
 */
class FieldErrorManager
{

    /**
     * The FormElement this FieldErrorManager belongs to.
     *
     * @var FormElement
     */
    private $formElement;

    /**
     * Array of errors for fields.
     *
     * @var array
     */
    private $errors = [];

    /**
     * Name of the Laravel errorBag, where this form should look for errors.
     *
     * @var string
     */
    protected $errorBag = 'default';

    /**
     * ValueManager constructor.
     *
     * @param FormElement $formElement
     */
    public function __construct(FormElement $formElement)
    {
        $this->formElement = $formElement;
    }

    /**
     * Set errors to be used for all fields.
     *
     * @param array $errors
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * Sets the name of the Laravel-errorBag, where this form should look for errors.
     * (default = 'default')
     *
     * @param string $errorBag
     */
    public function setErrorBag(string $errorBag)
    {
        $this->errorBag = $errorBag;
    }

    /**
     * Gets the error(s) of a field currently stored in the FormBuilder-object.
     *
     * @param string $fieldName
     * @return array
     */
    public function getErrorsForField(string $fieldName): array
    {
        $fieldName = FormBuilderTools::convertArrayFieldHtmlName2DotNotation($fieldName);

        if (isset($this->errors[$fieldName]) > 0) {
            return $this->errors[$fieldName];
        }

        // If no errors were found, we simply return an empty array.
        return [];
    }

    /**
     * If this form was submitted, fetch all validation-errors from laravel's session
     * and put them in $this->errors.
     */
    public function fetchErrorsFromSession()
    {
        // If $this->formElement was just submitted, we fetch any errors from the session
        // and put them into $this->errors (if no errors were manually set).
        if ($this->formElement->wasSubmitted && (count($this->errors) === 0) && session()->has('errors')) {
            $errorBag = session()->get('errors');
            if (is_a($errorBag, 'Illuminate\Support\ViewErrorBag')) {
                /** @var \Illuminate\Support\ViewErrorBag $errorBag */
                $errors = $errorBag->getBag($this->errorBag)->toArray();
                if (count($errors) > 0) {
                    $this->errors = $errors;
                }
            }
        }
    }

}
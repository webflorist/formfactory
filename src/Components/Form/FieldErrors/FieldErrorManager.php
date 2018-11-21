<?php

namespace Webflorist\FormFactory\Components\Form\FieldErrors;

use Webflorist\FormFactory\Components\Form\Form;
use Webflorist\FormFactory\Utilities\FormFactoryTools;

/**
 * Manages field-errors for form-instances.
 *
 * Class FieldErrorManager
 * @package Webflorist\FormFactory
 */
class FieldErrorManager
{

    /**
     * The Form this FieldErrorManager belongs to.
     *
     * @var Form
     */
    private $form;

    /**
     * Array of errors for fields.
     *
     * @var array
     */
    private $errors = [];

    /**
     * Array of unclaimed errors for fields.
     * This array is identical to $this->errors after initial generation
     * If getErrorsForField() is called for a field, it's errors get removed
     * from $this->unclaimedErrors. This way we can determine, if any errors
     * have not been displayed.
     *
     * @var array
     */
    private $unclaimedErrors = [];

    /**
     * Name of the Laravel errorBag, where this form should look for errors.
     *
     * @var string
     */
    protected $errorBag = 'default';

    /**
     * ValueManager constructor.
     *
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
        if ($this->form->wasSubmitted) {
            $this->fetchErrorsFromSession();
        }
    }

    /**
     * Set errors to be used for all fields.
     *
     * @param array $errors
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
        $this->unclaimedErrors = $errors;

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
     * Gets the error(s) of a field currently stored in this FieldErrorManager.
     * Also removes errors for this field from $this->unclaimedErrors.
     *
     * @param string $fieldName
     * @param bool $unclaimedOnly Returns errors only, if they have not been claimed before.
     * @return array
     */
    public function getErrorsForField(string $fieldName, $unclaimedOnly=false): array
    {
        $fieldName = FormFactoryTools::convertArrayFieldHtmlName2DotNotation($fieldName);

        if ($unclaimedOnly && !isset($this->unclaimedErrors[$fieldName])) {
            return [];
        }

        if (isset($this->errors[$fieldName])) {
            if (isset($this->unclaimedErrors[$fieldName])) {
                unset($this->unclaimedErrors[$fieldName]);
            }
            return $this->errors[$fieldName];
        }

        // If no errors were found, we simply return an empty array.
        return [];
    }

    /**
     *  Are any error(s) of a specific field currently stored in this FieldErrorManager?
     *
     * @param string $fieldName
     * @return bool
     */
    public function hasErrorsForField(string $fieldName): bool
    {
        if (isset($this->errors[FormFactoryTools::convertArrayFieldHtmlName2DotNotation($fieldName)])) {
            return true;
        }

        return false;
    }

    /**
     * If this form was submitted, fetch all validation-errors from laravel's session
     * and put them in $this->errors.
     */
    public function fetchErrorsFromSession()
    {
        // If $this->form was just submitted, we fetch any errors from the session
        // and put them into $this->errors (if no errors were manually set).
        if ($this->form->wasSubmitted && (count($this->errors) === 0) && session()->has('errors')) {
            $errorBag = session()->get('errors');
            if (is_a($errorBag, 'Illuminate\Support\ViewErrorBag')) {
                /** @var \Illuminate\Support\ViewErrorBag $errorBag */
                $errors = $errorBag->getBag($this->errorBag)->toArray();
                if (count($errors) > 0) {
                    $this->setErrors($errors);
                }
            }
        }
    }

    /**
     * Does this form have any errors set?
     */
    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    /**
     * Are any unclaimed errors present?
     *
     * @return bool
     */
    public function hasUnclaimedErrors()
    {
        return count($this->unclaimedErrors) > 0;
    }

    /**
     * Returns all unclaimed errors.
     *
     * @return array
     */
    public function getUnclaimedErrors()
    {
        return $this->unclaimedErrors;
    }

}
<?php

namespace Webflorist\FormFactory\Components\FormControls\Traits;

use Webflorist\FormFactory\Components\Helpers\FieldWrapper;
use Webflorist\FormFactory\FormFactory;
use Webflorist\FormFactory\Components\Helpers\ErrorContainer;
use Webflorist\FormFactory\Components\Form\FieldRules\FieldRuleManager;

/**
 * This traits provides error functionality for Fields.
 *
 * @package Webflorist\FormFactory
 */
trait ErrorsTrait
{

    /**
     * The ErrorContainer object used to manage errors for this Field.
     *
     * @var ErrorContainer
     */
    public $errors;

    /**
     * Set array of errors for this Field.
     * (Omit for automatic adoption from session)
     * Set to false to avoid rendering of errors.
     *
     * @param array|false $errors
     * @return $this
     */
    public function errors($errors)
    {
        if (is_array($errors)) {
            $this->errors->setErrors($errors);
        }
        if ($errors === false) {
            $this->errors->hideErrors();
        }
        return $this;
    }

    /**
     * Sets an additional field-name whose errors
     * should be displayed with this field's errors.
     *
     * @param string $fieldName
     * @return $this
     */
    public function addErrorField(string $fieldName)
    {
        $this->errors->addAdditionalErrorField($fieldName);
        return $this;
    }

}

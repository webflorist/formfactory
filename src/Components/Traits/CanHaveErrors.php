<?php

namespace Nicat\FormFactory\Components\Traits;

use Nicat\FormFactory\Utilities\FieldErrors\FieldErrors;

trait CanHaveErrors
{

    /**
     * The FieldErrors object used to manage errors for this field.
     *
     * @var null|false|FieldErrors
     */
    public $errors;

    /**
     * Set array of errors for this tag.
     * (omit for automatic adoption from session)
     * Set to false to avoid rendering of errors.
     *
     * @param array|false $errors
     * @return $this
     */
    public function errors($errors)
    {
        if (is_array($errors)) {
            $errors = (count($errors) > 0) ? (new FieldErrors($this))->setErrors($errors) : null;
        }
        $this->errors = $errors;
        return $this;
    }

}
<?php

namespace Webflorist\FormFactory\Components\FormControls\Contracts;

/**
 * This interface defines a "Field".
 * A Field is a form control, that has a 'name' attribute is not a button.
 * (<input>, <select>, <textarea>)
 *
 * @package Webflorist\FormFactory
 */
interface FieldInterface
{

    /**
     * Get the name of this field.
     *
     * @return string
     */
    function getFieldName() : string;

    /**
     * Can this Field have a label?
     *
     * @return bool
     */
    function canHaveLabel(): bool;

    /**
     * Apply a value to a field.
     *
     * @param $value
     */
    function applyFieldValue($value);

    /**
     * Does this field currently have a value set?
     *
     * @return bool
     */
    function fieldHasValue();


}
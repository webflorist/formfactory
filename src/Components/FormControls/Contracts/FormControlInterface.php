<?php

namespace Webflorist\FormFactory\Components\FormControls\Contracts;

use Webflorist\FormFactory\Components\Form\Form;

/**
 * This interface defines a "FormControl".
 * (<input>, <button>, <select>, <textarea>, <optgroup>, <option>)
 *
 * @package Webflorist\FormFactory
 */
interface FormControlInterface
{

    /**
     * Returns the Form this element belongs to.
     *
     * @return Form|null
     */
    function getForm();

    /**
     * Does this element belong to a Form?
     *
     * @return bool
     */
    function belongsToForm() : bool;

    /**
     * Is this FormControl a Field?
     *
     * @return bool
     */
    function isAField() : bool;

    /**
     * Can this FormControl have a help-text?
     *
     * @return bool
     */
    function canHaveHelpText(): bool;

    /**
     * Is vue enabled for this FormControl?
     *
     * @return bool
     */
    function isVueEnabled(): bool;

}


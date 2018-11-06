<?php

namespace Nicat\FormFactory\Components\Contracts;

use Nicat\FormFactory\Utilities\Forms\FormInstance;

/**
 * This interface defines a "FormControl".
 * (<input>, <button>, <select>, <textarea>, <optgroup>, <option>)
 *
 * @package Nicat\FormFactory
 */
interface FormControlInterface
{

    /**
     * Returns the FormInstance this element belongs to.
     *
     * @return FormInstance|null
     */
    function getFormInstance();

    /**
     * Does this element belong to a FormInstance?
     *
     * @return bool
     */
    function hasFormInstance() : bool;

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
     * Is vue enabled for this form-control?
     *
     * @return bool
     */
    function isVueEnabled(): bool;

}


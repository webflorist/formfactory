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
     * Sets the FormInstance this element belongs to.
     *
     * @param FormInstance|null $formInstance
     */
    public function setFormInstance(FormInstance $formInstance);

    /**
     * Returns the FormInstance this element belongs to.
     *
     * @return FormInstance|null
     */
    public function getFormInstance();

    /**
     * Does this element belong to a FormInstance?
     *
     * @return bool
     */
    public function hasFormInstance() : bool;

    /**
     * Is this FormControl a Field?
     *
     * @return bool
     */
    public function isAField() : bool;

    /**
     * Can this FormControl have a help-text?
     *
     * @return bool
     */
    public function canHaveHelpText(): bool;

}


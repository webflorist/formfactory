<?php

namespace Nicat\FormFactory\Components\Traits;

use Nicat\FormFactory\Utilities\Forms\FormInstance;

/**
 * This traits provides basic functionality for FormControls.
 *
 * @package Nicat\FormFactory
 */
trait FormControlTrait
{

    /**
     * The FormInstance this Field belongs to.
     *
     * @var null|FormInstance
     */
    private $formInstance = null;

    /**
     * Sets the FormInstance this Field belongs to.
     *
     * @param FormInstance|null $formInstance
     */
    public function setFormInstance(FormInstance $formInstance)
    {
        $this->formInstance = $formInstance;
    }

    /**
     * Returns the FormInstance this Field belongs to.
     *
     * @return FormInstance|null
     */
    public function getFormInstance()
    {
        return $this->formInstance;
    }

    /**
     * Does this Field belong to a FormInstance?
     *
     * @return bool
     */
    public function hasFormInstance() : bool
    {
        return !is_null($this->formInstance);
    }

    /**
     * Is this FormControl a Field?
     *
     * @return bool
     */
    public function isAField(): bool
    {
        return array_search(FieldInterface::class, class_implements($this));
    }

    /**
     * Can this FormControl have a help-text?
     *
     * @return bool
     */
    public function canHaveHelpText(): bool
    {
        return array_search(HelpTextInterface::class, class_implements($this));
    }

}
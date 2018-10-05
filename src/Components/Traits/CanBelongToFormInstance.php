<?php

namespace Nicat\FormFactory\Components\Traits;

use Nicat\FormFactory\Utilities\Forms\FormInstance;

trait CanBelongToFormInstance
{

    /**
     * The FormInstance this element belongs to.
     *
     * @var null|FormInstance
     */
    private $formInstance = null;

    /**
     * Sets the FormInstance this element belongs to.
     *
     * @param FormInstance|null $formInstance
     */
    public function setFormInstance(FormInstance $formInstance)
    {
        $this->formInstance = $formInstance;
    }

    /**
     * Returns the FormInstance this element belongs to.
     *
     * @return FormInstance|null
     */
    public function getFormInstance()
    {
        return $this->formInstance;
    }

    /**
     * Does this element belong to a FormInstance?
     *
     * @return bool
     */
    public function hasFormInstance()
    {
        return !is_null($this->formInstance);
    }

}
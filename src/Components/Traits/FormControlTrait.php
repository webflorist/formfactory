<?php

namespace Nicat\FormFactory\Components\Traits;

use Nicat\FormFactory\Components\Contracts\FieldInterface;
use Nicat\FormFactory\Components\Contracts\HelpTextInterface;
use Nicat\FormFactory\Exceptions\OpenElementNotFoundException;
use Nicat\FormFactory\FormFactory;
use Nicat\FormFactory\Utilities\FieldErrors\FieldErrors;
use Nicat\FormFactory\Utilities\FieldHelpTexts\FieldHelpText;
use Nicat\FormFactory\Utilities\FieldLabels\FieldLabel;
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
     * Performs various Setup-tasks for this FormControl.
     */
    protected function setupFormControl()
    {
        try {
            $this->formInstance = FormFactory::singleton()->getOpenForm();
            $this->formInstance->registerFormControl($this);
        } catch (OpenElementNotFoundException $e) {
        }

        if ($this->isAField()) {
            $this->errors = new FieldErrors($this);

            if ($this->canHaveLabel()) {
                $this->label = new FieldLabel($this);
            }
        }

        if ($this->canHaveHelpText()) {
            $this->helpText = new FieldHelpText($this);
        }
    }

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
<?php

namespace Webflorist\FormFactory\Components\FormControls\Traits;

use Webflorist\FormFactory\Components\Helpers\FieldLabel;

/**
 * This traits provides functionality for handling FieldLabels.
 *
 * @package Webflorist\FormFactory
 */
trait LabelTrait
{

    /**
     * The FieldLabel object used to manage the label for this field.
     *
     * @var FieldLabel
     */
    public $label = null;

    /**
     * Set content to be used for the label.
     * Omit for auto-translation.
     * Set to false to avoid rendering of label.
     *
     * @param string|false $label
     * @return $this
     */
    public function label($label)
    {
        if (is_string($label)) {
            $this->label->setText($label);
        }
        if ($label === false) {
            $this->label->hideLabel();
        }
        return $this;
    }

}
<?php

namespace Nicat\FormFactory\Components\Traits;

use Nicat\FormFactory\Utilities\FieldLabels\FieldLabel;

trait CanHaveLabel
{

    /**
     * The FieldLabel object used to manage the label for this field.
     *
     * @var null|false|FieldLabel
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
            $label = (strlen($label) > 0) ? (new FieldLabel($this))->setText($label) : null;
        }
        $this->label = $label;
        return $this;
    }

}
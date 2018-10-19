<?php

namespace Nicat\FormFactory\Components\Traits;

use Nicat\FormFactory\Components\Additional\FieldLabel;

trait CanHaveLabel
{
    /**
     * The FieldLabel-element for this field.
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
            $label = (new FieldLabel($this))->setText($label);
        }
        $this->label = $label;
        return $this;
    }

    /**
     * Does this field currently have a label set to be displayed.
     *
     * @return bool
     */
    public function hasLabel()
    {
        return is_a($this->label, FieldLabel::class);
    }

    /**
     * How the label should be applied.
     * Possible values are:
     * bound|before|after|sr-only|none
     *
     * @var null|string
     */
    public $labelMode = 'before';

    /**
     * Set how the label should be generated
     * (either bound|before|after|sr-only|none)
     *
     * @param string $labelMode : bound|before|after|sr-only|none
     * @return $this
     */
    public function labelMode(string $labelMode)
    {
        $this->labelMode = $labelMode;
        return $this;
    }

}
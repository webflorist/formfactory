<?php

namespace Nicat\FormBuilder\Elements\Traits;

trait CanHaveLabel
{
    /**
     * Content of the label.
     *
     * @var null|string
     */
    public $label = null;

    /**
     * Set content used for the label.
     * (omit for auto-translation)
     *
     * @param string $label
     * @return $this
     */
    public function label(string $label)
    {
        $this->label = $label;
        return $this;
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
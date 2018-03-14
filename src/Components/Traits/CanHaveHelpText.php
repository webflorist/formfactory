<?php

namespace Nicat\FormBuilder\Components\Traits;


trait CanHaveHelpText
{

    /**
     * A help-text to display with the field.
     *
     * @var null|string
     */
    protected $helpText = null;

    /**
     * Where the help-text should be displayed.
     *
     * @var string: before|after|append|prepend
     */
    public $helpTextLocation = 'after';

    /**
     * Set help-text to display.
     * (omit for auto-translation)
     *
     * @param string $helpText
     * @return $this
     */
    public function helpText($helpText)
    {
        $this->helpText = $helpText;
        return $this;
    }

    /**
     * Get help-text to display.
     *
     * @return null|string
     */
    public function getHelpText()
    {
        return $this->helpText;
    }

    /**
     * Is help-text set?
     *
     * @return bool
     */
    public function hasHelpText()
    {
        return strlen($this->helpText) > 0;
    }

}
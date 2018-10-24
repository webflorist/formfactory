<?php

namespace Nicat\FormFactory\Components\Contracts;

interface FieldLabelInterface
{

    /**
     * Get help-text to display.
     *
     * @return null|string
     */
    public function getHelpText();

    /**
     * Is help-text set?
     *
     * @return bool
     */
    public function hasHelpText();

    /**
     * Should a help-text be shown?
     *
     * @return bool
     */
    //public function showHelpText();

}
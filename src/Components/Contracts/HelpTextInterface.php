<?php

namespace Nicat\FormFactory\Components\Contracts;

/**
 * This interface defines a Field, that can have a help-text.
 *
 * Interface HelpTextInterface
 * @package Nicat\FormFactory
 */
interface HelpTextInterface
{

    /**
     * Set content to be used for the help-text.
     * Omit for auto-translation.
     * Set to false to avoid rendering of help-text.
     *
     * @param string|false $helpText
     * @return $this
     */
    public function helpText($helpText);

}
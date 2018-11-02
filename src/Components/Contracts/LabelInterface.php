<?php

namespace Nicat\FormFactory\Components\Contracts;

/**
 * This interface defines a Field, that can have a label.
 *
 * Interface LabelInterface
 * @package Nicat\FormFactory
 */
interface LabelInterface
{

    /**
     * Set content to be used for the label.
     * Omit for auto-translation.
     * Set to false to avoid rendering of label.
     *
     * @param string|false $label
     * @return $this
     */
    public function label($label);

}
<?php

namespace Nicat\FormFactory\Components\Traits;

use Nicat\FormFactory\Utilities\FieldHelpTexts\FieldHelpText;

/**
 * This traits provides a default implementation
 * for the HelpTextInterface.
 *
 * @package Nicat\FormFactory
 */
trait HelpTextTrait
{

    /**
     * The FieldHelp object used to store manage helpText for this field.
     *
     * @var null|false|FieldHelpText
     */
    public $helpText = null;

    /**
     * Set content to be used for the help-text.
     * Omit for auto-translation.
     * Set to false to avoid rendering of help-text.
     *
     * @param string|false $helpText
     * @return $this
     */
    public function helpText($helpText)
    {
        if (is_string($helpText)) {
            $helpText = (strlen($helpText) > 0) ? (new FieldHelpText($this))->setText($helpText) : null;
        }
        $this->helpText = $helpText;
        return $this;
    }

}
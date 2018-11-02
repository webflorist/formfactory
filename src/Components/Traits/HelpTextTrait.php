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
     * @var FieldHelpText
     */
    public $helpText;

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
            $this->helpText->setText($helpText);
        }
        if ($helpText === false) {
            $this->helpText->hideHelpText();
        }
        return $this;
    }

}
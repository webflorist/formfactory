<?php

namespace Webflorist\FormFactory\Components\FormControls\Traits;

use Webflorist\FormFactory\Components\Helpers\HelpTextContainer;

/**
 * This traits provides functionality for handling help-texts.
 *
 * @package Webflorist\FormFactory
 */
trait HelpTextTrait
{

    /**
     * The FieldHelp object used to store manage helpText for this field.
     *
     * @var HelpTextContainer
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
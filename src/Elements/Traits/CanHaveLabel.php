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

    /**
     * Generates the text to be used as a label
     *
     * @return string
     */
    private function generateLabelText()
    {
        // Determine label-content for the element.
        // If $this->label is defined, this one is used
        if (strlen($this->label) > 0) {
            $return = $this->label;
        } // Otherwise try to auto-translate.
        else {
            $return = $this->performAutoTranslation();

            // If auto-translation was unsuccessful, we use the upper-cased value, if this field is a radio-button.
            if ($return === null) {
                if (is_a($this, InputRadio::class)) {
                    $return = ucwords($this->getValue());
                } // In all other cases we use the upper-cased and array-stripped name
                else {
                    $return = ucwords($this->getArrayStrippedName());
                }
            }

        }
        // Apply required *, if field is required
        // (except for radio-buttons, because there we have the required * on the group-label).
        if ($this->isRequired()) {
            if (!(property_exists($this, 'attrType') AND ($this->attrType === 'radio'))) {
                $return .= '<sup>*</sup>';
                app()['formbuilder']->setMandatoryFieldState(true);
            }
        }
        return $return;
    }
}
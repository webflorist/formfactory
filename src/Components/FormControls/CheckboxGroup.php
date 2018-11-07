<?php

namespace Nicat\FormFactory\Components\FormControls;

use Nicat\FormFactory\Components\Additional\FieldErrors;
use Nicat\FormFactory\Components\Additional\FieldHelpText;
use Nicat\FormFactory\Components\Additional\RequiredFieldIndicator;
use Nicat\FormFactory\Components\Contracts\AutoTranslationInterface;
use Nicat\FormFactory\Components\Traits\AutoTranslationTrait;
use Nicat\FormFactory\Components\Traits\HelpTextTrait;
use Nicat\HtmlFactory\Elements\FieldsetElement;
use Nicat\HtmlFactory\Elements\LegendElement;

class CheckboxGroup
    extends FieldsetElement
{

    /**
     * The checkbox-input-elements contained in this checkbox-group.
     *
     * @var CheckboxInput[]
     */
    public $checkboxInputs;

    /**
     * Legend-Element for this checkbox-group.
     *
     * @var null|LegendElement
     */
    public $legend = null;

    /**
     * Array of all FieldHelpTexts, that are contained in this CheckboxGroup.
     *
     * @var FieldHelpText[]
     */
    public $containedHelpTexts = [];

    /**
     * Array of all FieldErrors, that are contained in this CheckboxGroup.
     *
     * @var FieldErrors[]
     */
    public $containedErrors = [];

    /**
     * CheckboxGroup constructor.
     *
     * @param CheckboxInput[] $checkboxInputs
     */
    public function __construct(array $checkboxInputs)
    {
        parent::__construct();

        $this->view('formfactory::form-controls.checkbox-group');

        $this->checkboxInputs = $checkboxInputs;

        // Set checkbox-buttons as content.
        $this->content($checkboxInputs);

    }

    /**
     * Set content to be used for the legend-tag.
     *
     * @param string|false $legend
     * @return $this
     */
    public function legend($legend) {
        if (is_null($this->legend)) {
            $this->legend = new LegendElement();
        }
        $this->legend->content($legend);
        return $this;
    }

    /**
     * Gets called before applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function afterDecoration()
    {

        // Clone HelpTexts and Errors from the checkbox-inputs.
        // Then set the field's ones to hide.
        foreach ($this->checkboxInputs as $checkboxInput) {

            // Tell checkbox-inputs, that they belong to a group.
            $checkboxInput->belongsToGroup = true;

            $this->containedErrors[] = clone $checkboxInput->errors;
            $checkboxInput->errors->hideErrors();

            if ($checkboxInput->canHaveHelpText()) {
                $this->containedHelpTexts[] = clone $checkboxInput->helpText;
                $checkboxInput->helpText->hideHelpText();
            }
        }

        // Make sure, all helpers are generated.
        foreach($this->containedHelpTexts as $helpText) {
            $helpText->generate();
        }
        foreach($this->containedErrors as $errors) {
            $errors->generate();
        }

    }


}
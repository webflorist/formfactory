<?php

namespace Nicat\FormFactory\Components\FormControls;

use Nicat\FormFactory\Components\Additional\FieldErrors;
use Nicat\FormFactory\Components\Additional\FieldHelpText;
use Nicat\FormFactory\Components\Contracts\AutoTranslationInterface;
use Nicat\FormFactory\Components\Traits\AutoTranslationTrait;
use Nicat\FormFactory\Components\Traits\HelpTextTrait;
use Nicat\HtmlFactory\Elements\FieldsetElement;
use Nicat\HtmlFactory\Elements\LegendElement;

class RadioGroup
    extends FieldsetElement
    implements AutoTranslationInterface
{
    use AutoTranslationTrait, HelpTextTrait;

    /**
     * Field-name of the contained radio-buttons.
     *
     * @var string
     */
    public $radioName;

    /**
     * The radio-input-elements contained in this radio-group.
     *
     * @var RadioInput[]
     */
    public $radioInputs;

    /**
     * Legend-Element for this radio-group.
     *
     * @var null|LegendElement
     */
    public $legend = null;

    /**
     * The FieldHelpTexts, that should be displayed with this RadioGroup.
     *
     * @var FieldHelpText
     */
    public $helpText = null;

    /**
     * The FieldErrors, that should be displayed with this RadioGroup.
     *
     * @var FieldErrors
     */
    public $errors = null;

    /**
     * RadioGroup constructor.
     *
     * @param string $name
     * @param RadioInput[] $radioInputs
     */
    public function __construct(string $name, array $radioInputs)
    {
        parent::__construct();

        $this->view('formfactory::form-controls.radio-group');

        $this->radioName = $name;
        $this->radioInputs = $radioInputs;
        $this->legend = new LegendElement();

        foreach ($radioInputs as $radioInput) {

            // Set name for all radio-buttons.
            $radioInput->name($name);

            // Do not display the RequiredFieldIndicator, since this will be done in this fieldset's legend.
            $radioInput->label->displayRequiredFieldIndicator = false;

            // Tell RadioInputs, that they belong to a RadioGroup.
            $radioInput->belongsToGroup = true;
        }

        // Set radio-buttons as content.
        $this->content($radioInputs);

    }

    /**
     * Set content to be used for the legend-tag.
     *
     * @param string|false $legend
     * @return $this
     */
    public function legend($legend) {
        $this->legend->content($legend);
        return $this;
    }

    /**
     * Gets called before applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function afterDecoration()
    {
        // Auto-translate legend.
        if (is_null($this->legend) && $this->legend !== false) {
            $this->legend($this->performAutoTranslation($this->radioName));
        }

        // Clone errors and help-texts from children and set them to hide.
        foreach ($this->radioInputs as $radioInput) {

            if ($this->errors === null) {
                $this->errors = clone $radioInput->errors;
            }
            $radioInput->errors->hideErrors();

            if ($this->helpText === null) {
                $this->helpText = clone $radioInput->helpText;
            }
            $radioInput->helpText->hideHelpText();

        }

        // Make sure, all helpers are generated.
        $this->errors->generate();
        $this->helpText->generate();
    }

    /**
     * Returns the base translation-key for auto-translations for this object.
     *
     * @return string
     */
    public function getAutoTranslationKey(): string
    {
        return $this->radioName;
    }


}
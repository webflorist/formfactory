<?php

namespace Webflorist\FormFactory\Components\FormControls;

use Webflorist\FormFactory\Components\Helpers\ErrorContainer;
use Webflorist\FormFactory\Components\Helpers\HelpTextContainer;
use Webflorist\FormFactory\Components\Helpers\RequiredFieldIndicator;
use Webflorist\FormFactory\Components\FormControls\Contracts\AutoTranslationInterface;
use Webflorist\FormFactory\Components\FormControls\Traits\AutoTranslationTrait;
use Webflorist\FormFactory\Components\FormControls\Traits\HelpTextTrait;
use Webflorist\HtmlFactory\Elements\FieldsetElement;
use Webflorist\HtmlFactory\Elements\LegendElement;

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
     * @var HelpTextContainer
     */
    public $helpText = null;

    /**
     * The ErrorContainer, that should be displayed with this RadioGroup.
     *
     * @var ErrorContainer
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

        $this->view('webflorist-formfactory::form-controls.radio-group');

        $this->radioName = $name;
        $this->radioInputs = $radioInputs;

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
        if (is_null($this->legend)) {
            $this->legend = new LegendElement();
        }
        if ($legend === false) {
            $this->legend->hidden();
        }
        else {
            $this->legend->content($legend);
        }
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
            $this->legend($this->performAutoTranslation(ucwords($this->radioName)));
        }

        if (!is_null($this->legend)) {
            $this->legend->appendContent(new RequiredFieldIndicator($this->radioInputs[0]));
        }

        // Clone errors and help-texts from children and set them to hide.
        foreach ($this->radioInputs as $radioInput) {

            if ($this->errors === null) {
                $this->errors = clone $radioInput->errors;
            }
            $radioInput->errors->hideErrors(true);

            // Generalize HelpText-ID so it is shared for all radio-inputs.
            $radioInput->helpText->id($radioInput->getForm()->getId() . '_' . $this->radioName . '_helpText');
            if ($this->helpText === null) {
                $this->helpText = clone $radioInput->helpText;
            }
            $radioInput->helpText->hideHelpText(true);
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

    /**
     * Defer calls to error-method to radio-inputs.
     *
     * @param array|false $errors
     * @return $this
     */
    public function errors($errors)
    {
        foreach ($this->radioInputs as $radioInput) {
            $radioInput->errors($errors);
        }
        return $this;
    }

    /**
     * Defer calls to rules-method to radio-inputs.
     *
     * @param string|array $rules
     * @return $this
     */
    public function rules($rules)
    {
        foreach ($this->radioInputs as $radioInput) {
            $radioInput->rules($rules);
        }
        return $this;
    }

    /**
     * Defer calls to helpText-method to radio-inputs.
     *
     * @param string|false $helpText
     * @return $this
     */
    public function helpText($helpText)
    {
        foreach ($this->radioInputs as $radioInput) {
            $radioInput->helpText($helpText);
        }
        return $this;
    }


}

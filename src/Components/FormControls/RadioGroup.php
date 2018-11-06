<?php

namespace Nicat\FormFactory\Components\FormControls;

use Nicat\FormFactory\Components\Additional\FieldsetLegend;
use Nicat\FormFactory\Components\Contracts\AutoTranslationInterface;
use Nicat\FormFactory\Components\Contracts\FieldInterface;
use Nicat\FormFactory\Components\Contracts\FormControlInterface;
use Nicat\FormFactory\Components\Contracts\HelpTextInterface;
use Nicat\FormFactory\Components\Contracts\LabelInterface;
use Nicat\FormFactory\Components\Traits\AutoTranslationTrait;
use Nicat\FormFactory\Components\Traits\FieldTrait;
use Nicat\FormFactory\Components\Traits\FormControlTrait;
use Nicat\FormFactory\Components\Traits\HelpTextTrait;
use Nicat\FormFactory\Components\Traits\LabelTrait;
use Nicat\HtmlFactory\Elements\FieldsetElement;

class RadioGroup
    extends FieldsetElement
    implements FormControlInterface, FieldInterface, LabelInterface, HelpTextInterface, AutoTranslationInterface
{
    use FormControlTrait,
        FieldTrait,
        LabelTrait,
        HelpTextTrait,
        AutoTranslationTrait;

    /**
     * Field-name of the contained radio-buttons.
     *
     * @var string
     */
    public $radioName;

    /**
     * The radio-input-elements contained in this radio-group.
     *
     * @var string
     */
    public $radioInputs;

    /**
     * Legend-Element for this radio-group.
     *
     * @var null|FieldsetLegend
     */
    public $legend = null;

    /**
     * RadioGroup constructor.
     *
     * @param string $name
     * @param RadioInput[] $radioInputs
     */
    public function __construct(string $name, array $radioInputs)
    {
        parent::__construct();
        $this->radioName = $name;
        $this->radioInputs = $radioInputs;
        $this->legend = new FieldsetLegend($this);
        $this->setupFormControl();
        $this->wrap(false);

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
        $this->legend->setText($legend);
        return $this;
    }

    /**
     * Gets called before applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function beforeDecoration()
    {
        $this->processFormControl();

        // Auto-translate legend.
        if (is_null($this->legend) && $this->legend !== false) {
            $this->legend($this->performAutoTranslation($this->radioName));
        }

        foreach ($this->content->getChildrenByClassName(RadioInput::class) as $childKey => $child) {
            /** @var RadioInput $child */

            // Clone errors and help-texts to children and set them to hide.
            $child->errors = clone $this->errors;
            $child->helpText = clone $this->helpText;
            $child->errors->hideErrors();
            $child->helpText->hideHelpText();
        }
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
     * @inheritDoc
     */
    public function getFieldName(): string
    {
        return $this->radioName;
    }

    /**
     * Defer setting of aria-invalid attribute to radio-inputs.
     *
     * @param $invalid
     * @return $this
     */
    public function ariaInvalid($invalid)
    {
        foreach ($this->radioInputs as $radioInput) {
            $radioInput->ariaInvalid($invalid);
        }
        return $this;
    }

    /**
     * Defer setting of aria-describedby attribute to radio-inputs.
     *
     * @param $id
     * @return $this
     */
    public function addAriaDescribedby($id)
    {
        foreach ($this->radioInputs as $radioInput) {
            $radioInput->addAriaDescribedby($id);
        }
        return $this;
    }

    /**
     * Defer setting of rules to radio-inputs.
     *
     * @param $rules
     * @return $this
     */
    public function rules($rules)
    {
        foreach ($this->radioInputs as $radioInput) {
            $radioInput->rules($rules);
        }
        return $this;
    }


}
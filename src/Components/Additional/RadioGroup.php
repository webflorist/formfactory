<?php

namespace Nicat\FormFactory\Components\Additional;

use Nicat\FormFactory\Components\Contracts\AutoTranslationInterface;
use Nicat\FormFactory\Components\Contracts\FieldInterface;
use Nicat\FormFactory\Components\Contracts\FormControlInterface;
use Nicat\FormFactory\Components\Contracts\HelpTextInterface;
use Nicat\FormFactory\Components\Contracts\LabelInterface;
use Nicat\FormFactory\Components\FormControls\RadioInput;
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
     * Legend-Text for this radio-group.
     *
     * @var null|string
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

        $this->setupFormControl();


        foreach ($radioInputs as $radioInput) {

            // Set name for all radio-buttons.
            $radioInput->name($name);

            // Share FieldErrors and FieldHelpText between Radio-buttons.
            $radioInput->errors = $this->errors;
            $radioInput->helpText = $this->helpText;
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
        $this->legend = $legend;
        return $this;
    }

    /**
     * Gets called before applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function beforeDecoration()
    {
        // Auto-translate legend.
        if (is_null($this->legend) && $this->legend !== false) {
            $this->legend($this->performAutoTranslation($this->radioName));
        }

        foreach ($this->content->getChildrenByClassName(RadioInput::class) as $childKey => $child) {
            /** @var FieldTrait $child */
            $child->errors->hideErrors();
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


}
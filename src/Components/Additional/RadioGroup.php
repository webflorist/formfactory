<?php

namespace Nicat\FormFactory\Components\Additional;

use Nicat\FormFactory\Components\Traits\CanHaveHelpText;
use Nicat\FormFactory\Utilities\AutoTranslation\AutoTranslationInterface;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\FormFactory\Components\Traits\CanHaveErrors;
use Nicat\FormFactory\Components\Traits\UsesAutoTranslation;
use Nicat\HtmlFactory\Elements\FieldsetElement;

class RadioGroup extends FieldsetElement implements AutoTranslationInterface
{

    use UsesAutoTranslation,
        CanHaveHelpText;

    /**
     * Field-name of the contained radio-buttons.
     *
     * @var string
     */
    protected $radioName;

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

        // Set name for all radio-buttons.
        foreach ($radioInputs as $radioInput) {
            $radioInput->name($name);
        }

        // Set radio-buttons as content.
        $this->content($radioInputs);

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
    }

    /**
     * Gets called after applying decorators to the child-elements.
     * Overwrite to perform manipulations.
     */
    protected function afterChildrenDecoration()
    {
        foreach ($this->content->getChildrenByClassName(RadioInput::class) as $childKey => $child) {
            /** @var CanHaveErrors $child */
           //$child->showErrors(false);
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

}
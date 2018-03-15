<?php

namespace Nicat\FormBuilder\Components\Additional;

use Nicat\FormBuilder\Utilities\AutoTranslation\AutoTranslationInterface;
use Nicat\FormBuilder\Components\FormControls\RadioInput;
use Nicat\FormBuilder\Components\Traits\CanHaveErrors;
use Nicat\FormBuilder\Components\Traits\UsesAutoTranslation;
use Nicat\HtmlBuilder\Elements\FieldsetElement;

class RadioGroup extends FieldsetElement implements AutoTranslationInterface
{

    use UsesAutoTranslation;

    /**
     * Any errors for radio-buttons contained in this radio-group will be displayed here.
     *
     * @var ErrorWrapper
     */
    private $errorWrapper;

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

        // Set $this->errorWrapper and prepend it.
        $this->errorWrapper = (new ErrorWrapper());
        $this->errorWrapper->addErrorField($name);
        $this->prependContent($this->errorWrapper);

        // Auto-translate legend.
        $this->legend($this->performAutoTranslation($this->radioName));
    }

    /**
     * Gets called after applying decorators to the child-elements.
     * Overwrite to perform manipulations.
     */
    protected function afterChildrenDecoration()
    {
        foreach ($this->content->getChildrenByClassName(RadioInput::class) as $childKey => $child) {
            /** @var CanHaveErrors $child */
            $child->showErrors(false);
        }
    }

    /**
     * Returns the base translation-key for auto-translations for this object.
     *
     * @return string
     */
    function getAutoTranslationKey(): string
    {
        return $this->radioName;
    }

}
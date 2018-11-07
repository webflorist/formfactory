<?php

namespace Nicat\FormFactory\Components\FormControls;

use Nicat\FormFactory\Components\Traits\FormControlTrait;
use Nicat\FormFactory\Components\Contracts\FormControlInterface;
use Nicat\FormFactory\Components\Contracts\AutoTranslationInterface;
use Nicat\FormFactory\Components\Traits\AutoTranslationTrait;
use Nicat\FormFactory\FormFactory;
use Nicat\FormFactory\Utilities\FormFactoryTools;
use Nicat\HtmlFactory\Elements\OptionElement;

class Option
    extends OptionElement
    implements FormControlInterface, AutoTranslationInterface
{

    use FormControlTrait,
        AutoTranslationTrait;

    /**
     * Option constructor.
     *
     * @param string $value
     */
    public function __construct($value = '')
    {
        parent::__construct();
        $this->value($value);
        $this->setupFormControl();
    }

    /**
     * Gets called after applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function afterDecoration()
    {
        parent::beforeDecoration();
        $this->processFormControl();

        // Auto-translate option-text, if none was set.
        if (!$this->content->hasContent()) {
            $this->content(
                $this->performAutoTranslation($this->attributes->value)
            );
        }
    }

    /**
     * Returns the base translation-key for auto-translations for this object.
     *
     * @return string
     * @throws \Nicat\FormFactory\Exceptions\OpenElementNotFoundException
     */
    public function getAutoTranslationKey(): string
    {
        /** @var FormFactory $formFactoryService */
        $formFactoryService = app()[FormFactory::class];
        return
            FormFactoryTools::arrayStripString($formFactoryService->getOpenForm()->getLastSelect()->attributes->name) .
            '_' .
            $this->attributes->value;
    }
}
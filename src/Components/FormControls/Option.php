<?php

namespace Webflorist\FormFactory\Components\FormControls;

use Webflorist\FormFactory\Components\FormControls\Traits\FormControlTrait;
use Webflorist\FormFactory\Components\FormControls\Contracts\FormControlInterface;
use Webflorist\FormFactory\Components\FormControls\Contracts\AutoTranslationInterface;
use Webflorist\FormFactory\Components\FormControls\Traits\AutoTranslationTrait;
use Webflorist\FormFactory\FormFactory;
use Webflorist\FormFactory\Utilities\FormFactoryTools;
use Webflorist\HtmlFactory\Elements\OptionElement;

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
     * @throws \Webflorist\FormFactory\Exceptions\OpenElementNotFoundException
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
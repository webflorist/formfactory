<?php

namespace Nicat\FormFactory\Components\FormControls;

use Nicat\FormFactory\Utilities\AutoTranslation\AutoTranslationInterface;
use Nicat\FormFactory\Components\Traits\UsesAutoTranslation;
use Nicat\FormFactory\FormFactory;
use Nicat\FormFactory\Utilities\FormFactoryTools;
use Nicat\HtmlFactory\Elements\OptionElement;

class Option extends OptionElement implements AutoTranslationInterface
{

    use UsesAutoTranslation;

    /**
     * Returns the base translation-key for auto-translations for this object.
     *
     * @return string
     * @throws \Nicat\FormFactory\Exceptions\OpenElementNotFoundException
     */
    function getAutoTranslationKey(): string
    {
        /** @var FormFactory $formFactoryService */
        $formFactoryService = app()[FormFactory::class];
        return
            FormFactoryTools::arrayStripString($formFactoryService->getOpenSelect()->attributes->name) .
            '_' .
            $this->attributes->value;
    }
}
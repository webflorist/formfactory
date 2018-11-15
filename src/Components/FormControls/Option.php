<?php

namespace Webflorist\FormFactory\Components\FormControls;

use Webflorist\FormFactory\Utilities\AutoTranslation\AutoTranslationInterface;
use Webflorist\FormFactory\Components\Traits\UsesAutoTranslation;
use Webflorist\FormFactory\FormFactory;
use Webflorist\FormFactory\Utilities\FormFactoryTools;
use Webflorist\HtmlFactory\Elements\OptionElement;

class Option extends OptionElement implements AutoTranslationInterface
{

    use UsesAutoTranslation;

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
            FormFactoryTools::arrayStripString($formFactoryService->getOpenSelect()->attributes->name) .
            '_' .
            $this->attributes->value;
    }
}
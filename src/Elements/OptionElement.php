<?php

namespace Nicat\FormBuilder\Elements;

use Nicat\FormBuilder\AutoTranslation\AutoTranslationInterface;
use Nicat\FormBuilder\Elements\Traits\UsesAutoTranslation;
use Nicat\FormBuilder\FormBuilder;
use Nicat\FormBuilder\FormBuilderTools;

class OptionElement extends \Nicat\HtmlBuilder\Elements\OptionElement implements AutoTranslationInterface
{

    use UsesAutoTranslation;

    /**
     * Returns the base translation-key for auto-translations for this object.
     *
     * @return string
     */
    function getAutoTranslationKey(): string
    {
        /** @var FormBuilder $formBuilderService */
        $formBuilderService = app()[FormBuilder::class];
        return
            FormBuilderTools::arrayStripString($formBuilderService->openSelect->attributes->name) .
            '_' .
            $this->attributes->value;
    }
}
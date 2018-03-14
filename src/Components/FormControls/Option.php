<?php

namespace Nicat\FormBuilder\Components\FormControls;

use Nicat\FormBuilder\Utilities\AutoTranslation\AutoTranslationInterface;
use Nicat\FormBuilder\Components\Traits\UsesAutoTranslation;
use Nicat\FormBuilder\FormBuilder;
use Nicat\FormBuilder\Utilities\FormBuilderTools;
use Nicat\HtmlBuilder\Elements\OptionElement;

class Option extends OptionElement implements AutoTranslationInterface
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
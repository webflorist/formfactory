<?php

namespace Nicat\FormFactory\Components\FormControls;

use Nicat\FormFactory\Utilities\AutoTranslation\AutoTranslationInterface;
use Nicat\FormFactory\Components\Traits\UsesAutoTranslation;
use Nicat\HtmlFactory\Components\ResetButtonComponent;
use Nicat\HtmlFactory\Components\Traits\HasContext;

class ResetButton extends ResetButtonComponent implements AutoTranslationInterface
{
    use UsesAutoTranslation,
        HasContext;

    /**
     * Returns the base translation-key for auto-translations for this object.
     *
     * @return string
     */
    function getAutoTranslationKey(): string
    {
        // If the "name"-attribute is set, we use that as the translation-key.
        if ($this->attributes->isSet('name')) {
            return $this->attributes->name;
        }

        // If the button has a "context", we use that as the translation-key.
        if ($this->hasContext()) {
            return $this->getContext();
        }

        // As default-suffix, we use the string 'reset'.
        return 'reset';
    }
}
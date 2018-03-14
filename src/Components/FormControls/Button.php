<?php

namespace Nicat\FormBuilder\Components\FormControls;

use Nicat\FormBuilder\Utilities\AutoTranslation\AutoTranslationInterface;
use Nicat\FormBuilder\Components\Traits\UsesAutoTranslation;
use Nicat\HtmlBuilder\Components\Traits\HasContext;
use Nicat\HtmlBuilder\Elements\ButtonElement;

class Button extends ButtonElement implements AutoTranslationInterface
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

        // As default-suffix, we use the string 'submit'.
        return 'submit';
    }
}
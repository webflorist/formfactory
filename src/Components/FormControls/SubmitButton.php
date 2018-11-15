<?php

namespace Webflorist\FormFactory\Components\FormControls;

use Webflorist\FormFactory\Utilities\AutoTranslation\AutoTranslationInterface;
use Webflorist\FormFactory\Components\Traits\UsesAutoTranslation;
use Webflorist\HtmlFactory\Components\SubmitButtonComponent;
use Webflorist\HtmlFactory\Components\Traits\HasContext;

class SubmitButton extends SubmitButtonComponent implements AutoTranslationInterface
{
    use UsesAutoTranslation,
        HasContext;

    /**
     * Returns the base translation-key for auto-translations for this object.
     *
     * @return string
     */
    public function getAutoTranslationKey(): string
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
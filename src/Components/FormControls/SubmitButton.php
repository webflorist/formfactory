<?php

namespace Nicat\FormFactory\Components\FormControls;

use Nicat\FormFactory\Utilities\AutoTranslation\AutoTranslationInterface;
use Nicat\FormFactory\Components\Traits\UsesAutoTranslation;
use Nicat\HtmlFactory\Components\SubmitButtonComponent;
use Nicat\HtmlFactory\Components\Traits\HasContext;

class SubmitButton extends SubmitButtonComponent implements AutoTranslationInterface
{
    use UsesAutoTranslation,
        HasContext;

    /**
     * SubmitButton constructor.
     *
     * @param string $name
     */
    public function __construct(string $name = 'submit')
    {
        parent::__construct();
        $this->name($name);
    }

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
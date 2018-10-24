<?php

namespace Nicat\FormFactory\Components\FormControls;

use Nicat\FormFactory\Components\Traits\CanBelongToFormInstance;
use Nicat\FormFactory\Utilities\AutoTranslation\AutoTranslationInterface;
use Nicat\FormFactory\Components\Traits\UsesAutoTranslation;
use Nicat\FormFactory\Utilities\Forms\FormInstance;
use Nicat\HtmlFactory\Components\Traits\HasContext;
use Nicat\HtmlFactory\Elements\ButtonElement;

class Button extends ButtonElement implements AutoTranslationInterface
{
    use UsesAutoTranslation,
        CanBelongToFormInstance,
        HasContext;

    /**
     * Button constructor.
     *
     * @param string $name
     */
    public function __construct(string $name=null)
    {
        parent::__construct();
        if (!is_null($name)) {
            $this->name($name);
        }
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
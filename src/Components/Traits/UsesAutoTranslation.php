<?php

namespace Nicat\FormFactory\Components\Traits;

use Nicat\FormFactory\Utilities\AutoTranslation\AutoTranslator;
use Nicat\FormFactory\Utilities\FormFactoryTools;

trait UsesAutoTranslation
{
    /**
     * Perform auto-translations for this object?
     *
     * @var bool
     */
    protected $autoTranslate = true;

    /**
     * Turn auto-translation for this element on/off.
     *
     * @param bool $autoTranslate
     * @return $this
     */
    public function autoTranslate(bool $autoTranslate)
    {
        $this->autoTranslate = $autoTranslate;
        return $this;
    }

    /**
     * Tries to perform an auto-translation.
     *
     * Calling Class must implement AutoTranslationInterface!
     *
     * The $suffix is appended to the translation-key (e.g. "HelpText" or "Placeholder").
     *
     * @param null $defaultValue
     * @param string $suffix
     * @return null|string
     */
    public function performAutoTranslation($defaultValue = null, $suffix = '')
    {
        if ($this->autoTranslate) {
            return AutoTranslator::autoTranslateObject($this, $defaultValue, $suffix);
        }
        return $defaultValue;
    }

    /**
     * Returns the base translation-key for auto-translations for this object.
     * Override for special behaviour.
     *
     * @return string
     */
    function getAutoTranslationKey(): string
    {
        return FormFactoryTools::arrayStripString($this->attributes->name);
    }
}
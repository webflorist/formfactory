<?php

namespace Nicat\FormFactory\Components\FormControls\Contracts;

/**
 * This interface defines a FormControl, that can use auto-translation.
 *
 * @package Nicat\FormFactory
 */
interface AutoTranslationInterface
{
    /**
     * Turn auto-translation for this object on/off.
     *
     * @param bool $autoTranslate
     * @return $this
     */
    public function autoTranslate(bool $autoTranslate);

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
    public function performAutoTranslation($defaultValue = null, $suffix = '');

    /**
     * Returns the base translation-key for auto-translations for this object.
     *
     * @return string
     */
    public function getAutoTranslationKey() : string;

}
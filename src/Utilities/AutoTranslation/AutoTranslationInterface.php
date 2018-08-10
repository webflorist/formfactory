<?php

namespace Nicat\FormFactory\Utilities\AutoTranslation;

interface AutoTranslationInterface
{

    /**
     * Returns the base translation-key for auto-translations for this object.
     *
     * @return string
     */
    public function getAutoTranslationKey() : string;

}
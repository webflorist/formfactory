<?php

namespace Nicat\FormBuilder\Utilities\AutoTranslation;

interface AutoTranslationInterface
{

    /**
     * Returns the base translation-key for auto-translations for this object.
     *
     * @return string
     */
    function getAutoTranslationKey() : string;

}
<?php

namespace Nicat\FormBuilder\Elements\Traits;

use Nicat\FormBuilder\Elements\OptionElement;
use Nicat\FormBuilder\Elements\RadioInputElement;
use Nicat\FormBuilder\FormBuilder;
use Nicat\FormBuilder\FormBuilderTools;

trait UsesAutoTranslation
{
    /**
     * Auto-translation for this tag.
     *
     * @var bool
     */
    protected $autoTranslate = true;

    /**
     * Set auto-translation for this tag.
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
     * Is auto-translation enabled for this tag?
     *
     * @return bool
     */
    public function doesAutoTranslate() : bool
    {
        return $this->autoTranslate;
    }

    /**
     * Tries to perform an auto-translation.
     *
     * Calling Class must have method "getTranslationKey" or state the translationKey via $translationKey.
     *
     * The $suffix is appended to the translation-key (e.g. "HelpText" or "Placeholder").
     *
     * @param null $translationKey
     * @param string $suffix
     * @param null $defaultValue
     * @return null|string
     */
    public function performAutoTranslation($translationKey = null, $suffix = '', $defaultValue = null)
    {
        if ($this->doesAutoTranslate()) {
            if (is_null($translationKey)) {
                $translationKey = $this->getTranslationKey();
            }
            // Append the suffix (e.g. "Placeholder" or "HelpText")
            $translationKey .= $suffix;
            // Perform auto-translation
            return FormBuilderTools::autoTranslate($translationKey, $defaultValue);
        }
        return $defaultValue;
    }

    /**
     * Returns the base translation-key for auto-translations for this field.
     * (By default this will be the array-stripped name of the field.)
     */
    private function getTranslationKey() {

        if (is_a($this, RadioInputElement::class)) {
            return $this->attributes->getValue('value');
        }

        if (is_a($this, OptionElement::class)) {
            /** @var FormBuilder $formBuilderService */
            $formBuilderService = app()[FormBuilder::class];
            return
                FormBuilderTools::arrayStripString(
                    $formBuilderService->openSelect->attributes->getValue('name')
                ) .
                '_' . $this->attributes->getValue('value');
        }

        return FormBuilderTools::arrayStripString($this->attributes->getValue('name'));
    }
}
<?php

namespace Nicat\FormFactory\Utilities\AutoTranslation;

use Lang;
use Nicat\ExtendedValidation\ExtendedValidation;
use Nicat\FormFactory\Components\Contracts\AutoTranslationInterface;
use Nicat\RouteTree\RouteTree;

class AutoTranslator
{

    /**
     * Static function to auto-translate using an object implementing AutoTranslationInterface.
     *
     * @param AutoTranslationInterface $object
     * @param string $suffix
     * @param string|null $defaultValue
     * @return null|string
     */
    public static function autoTranslateObject(AutoTranslationInterface $object, string $defaultValue = null, string $suffix = '')
    {
        return self::autoTranslate($object->getAutoTranslationKey() . $suffix, $defaultValue);
    }

    /**
     * Static function to auto-translate a string.
     *
     * @param $translationKey
     * @param null|string $defaultValue
     * @return null|string
     */
    public static function autoTranslate(string $translationKey, string $defaultValue = null)
    {

        // If the nicat/routetree package is installed,
        // we try getting a translation from the 'form' array of the node's contentLangFile.
        if (isset(app()[RouteTree::class])) {
            $translationString = route_tree()->getCurrentNode()->getContentLangFile() . '.form.' . $translationKey;
            if (Lang::has($translationString)) {
                return trans($translationString);
            }
        }

        // If the nicat/extended-validation package is installed,
        // we try getting a translation from it's registered attributes.
        if (isset(app()[ExtendedValidation::class]) && app()[ExtendedValidation::class]->hasAttribute($translationKey)) {
            return app()[ExtendedValidation::class]->getAttribute($translationKey);
        }

        // Otherwise, we try to translate from the language file,
        // that is defined under the config key "formfactory.translations".
        $translationString = config('formfactory.translations') . '.' . $translationKey;
        if (Lang::has($translationString)) {
            return trans($translationString);
        }

        // Per default we return the stated $defaultValue.
        return $defaultValue;
    }

}
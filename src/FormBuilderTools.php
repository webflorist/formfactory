<?php

namespace Nicat\FormBuilder;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Nicat\ExtendedValidation\ExtendedValidation;
use Nicat\FormBuilder\Exceptions\FormRequestClassNotFoundException;

/**
 * This class provides some static functions for general use.
 *
 * Class FormBuilder
 * @package Nicat\FormBuilder
 *
 */
class FormBuilderTools
{

    /**
     * Parses a string or array of Laravel-rules
     * into an array structured as:
     *
     *  [rule] => array(parameters)
     *
     * @param string|array $rules
     * @return array
     */
    public static function parseRules($rules)
    {
        $return = [];
        $explodedRules = null;
        if (is_string($rules) && (strlen($rules) > 0)) {
            $explodedRules = explode('|', $rules);
        } else if (is_array($rules) && (count($rules) > 0)) {
            $explodedRules = $rules;
        }
        if (is_array($explodedRules) && (count($explodedRules) > 0)) {
            foreach ($explodedRules as $key => $rule) {
                $parameters = [];
                if (str_contains($rule, ':')) {
                    $ruleWithParameters = explode(':', $rule);
                    $rule = $ruleWithParameters[0];
                    $parameters = explode(',', $ruleWithParameters[1]);
                }
                $return[$rule] = $parameters;
            }
        }
        return $return;
    }

    /**
     * Checks, if a field is part of an array (e.g. "domainlist[0][domainName][domainLabel]").
     *
     * @param string $fieldName
     * @return bool
     */
    public static function isArrayField(string $fieldName): bool
    {
        return ((strpos($fieldName, '[') !== false) AND (strpos($fieldName, ']') !== false));
    }

    /**
     * Converts the HTML-name of an array-field (e.g. "domainlist[0][domainName][domainLabel]")
     * to it's DOT-notation (e.g. "domainlist.0.domainName.domainLabel")
     *
     * @param string $fieldName
     * @return string
     */
    public static function convertArrayFieldHtmlName2DotNotation(string $fieldName): string
    {
        if (static::isArrayField($fieldName)) {
            $fieldName = str_replace('[', '.', $fieldName);
            $fieldName = str_replace(']', '', $fieldName);
        }
        // Remove any dots from the end of the name.
        $fieldName = rtrim($fieldName, '.');
        return $fieldName;
    }

    /**
     * Converts the DOT-notation of an array-field (e.g. "domainlist.0.domainName.domainLabel")
     * to it's HTML-name (e.g. "domainlist[0][domainName][domainLabel]").
     *
     * @param string $fieldName
     * @return string
     */
    public static function convertArrayFieldDotNotation2HtmlName(string $fieldName): string
    {
        if (strpos($fieldName, '.') !== false) {
            $fieldName = str_replace('.', '][', $fieldName) . ']';
            $fieldName = preg_replace('/]/', '', $fieldName, 1);
        }
        return $fieldName;
    }

    /**
     * Tries to auto-translate a string.
     *
     * @param $translationKey
     * @param null $defaultValue
     * @return null|string
     */
    public static function autoTranslate($translationKey, $defaultValue = null)
    {

        // If the nicat/extended-validation package is installed,
        // we try getting a translation from it's registered attributes.
        if (isset(app()[ExtendedValidation::class]) && app()[ExtendedValidation::class]->hasAttribute($translationKey)) {
            return app()[ExtendedValidation::class]->getAttribute($translationKey);
        }

        // Otherwise, we try to translate from the language file,
        // that is defined under the config key "formbuilder.translations".
        $translationString = config('formbuilder.translations') . '.' . $translationKey;
        if (\Lang::has($translationString)) {
            return trans($translationString);
        }

        // Per default we return the stated $defaultValue.
        return $defaultValue;
    }

    /**
     * Strips a string (normally a field-name) of it's array-structure and only returns it's most specific field-name.
     * e.g. 'myFieldArray[]'                    returns 'myFieldArray',
     *      'myFieldArray[][mySpecificField]    returns 'mySpecificField'
     *
     * @param $string
     * @return string
     */
    public static function arrayStripString(string $string): string
    {
        if ((strpos($string, '[') !== false) AND (strpos($string, ']') !== false)) {
            return substr($string, strrpos($string, '[') + 1, -1);
        } else {
            return $string;
        }
    }

    /**
     * Returns the string to be used as the honeypot-field-name.
     *
     * @return string
     */
    public static function getHoneypotFieldName()
    {
        return md5(csrf_token());
    }

    /**
     * Gets instance of FormRequest-Object
     *
     * @param string $objectName
     * @return FormRequest
     * @throws FormRequestClassNotFoundException
     */
    public static function initFormRequestObject(string $objectName) : FormRequest
    {
        if (!class_exists($objectName)) {
            throw new FormRequestClassNotFoundException('The form request class ' . $objectName . ' could not be found!');
        }
        return new $objectName();
    }

    /**
     * Generates captcha-data (question and answer).
     *
     * @return array
     */
    public static function generateCaptchaData() {
        $num1 = rand(1, 10) * rand(1, 3);
        $num2 = rand(1, 10) * rand(1, 3);
        $answer = $num1+$num2;
        $question = trans('Nicat-FormBuilder::formbuilder.captcha_questions.math',['calc' => $num1.' + '.$num2]);
        return [
            'question' => $question,
            'answer' => $answer,
        ];
    }


}
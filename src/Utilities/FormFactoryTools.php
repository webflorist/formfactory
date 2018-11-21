<?php

namespace Webflorist\FormFactory\Utilities;

use Illuminate\Foundation\Http\FormRequest;
use Webflorist\FormFactory\Exceptions\FormRequestClassNotFoundException;

/**
 * This class provides some static functions for general use.
 *
 * Class FormFactory
 * @package Webflorist\FormFactory
 *
 */
class FormFactoryTools
{

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
     * Returns all parent-array-field-names of an array.
     * E.g. for "domainlist[0][domainName][domainLabel]" the following array is returned:
     * [
     *      'domainlist',
     *      'domainlist[0]',
     *      'domainlist[0][domainName]'     *
     * ]
     *
     * @param string $fieldName
     * @return array
     */
    public static function getArrayFieldParents(string $fieldName): array
    {
        $fieldParents = [];
        if (self::isArrayField($fieldName)) {
            $immediateParent = substr($fieldName, 0, strrpos($fieldName, '['));
            $fieldParents[] = $immediateParent;
            if (self::isArrayField($immediateParent)) {
                $fieldParents = array_merge($fieldParents, self::getArrayFieldParents($immediateParent));
            }
        }
        return $fieldParents;
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
     * Converts the HTML-name of an array-field (e.g. "domainlist[0][domainName][domainLabel]")
     * to a JavaScript/Vue friendly notation (e.g. "domainlist.0.domainName.domainLabel")
     *
     * @param string $fieldName
     * @return string
     */
    public static function convertArrayFieldHtmlName2JsNotation(string $fieldName): string
    {
        if (static::isArrayField($fieldName)) {
            $fieldName = str_replace('[', '_', $fieldName);
            $fieldName = str_replace(']', '', $fieldName);
        }
        // Remove any dots from the end of the name.
        $fieldName = rtrim($fieldName, '_');
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
            $explodedArray = explode('.',self::convertArrayFieldHtmlName2DotNotation($string));
            $return = $explodedArray[0];
            foreach($explodedArray as $segment) {
                if ((strlen($segment) > 0) && (!is_numeric($segment)) && (preg_match('/^%group\w+itemID%/',$segment) === 0)) {
                    $return = $segment;
                }
            }
            return $return;
        }

        return $string;
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
     * Saves last_form_request_object key inside session.
     *
     * @param string $fullClassName
     */
    public static function saveLastFormRequestObject(string $fullClassName)
    {
        session()->put('formfactory.last_form_request_object', $fullClassName);
    }


}
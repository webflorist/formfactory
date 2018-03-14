<?php

namespace Nicat\FormBuilder\Utilities;

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


}
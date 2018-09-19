<?php

namespace Nicat\FormFactory\Utilities\FieldRules;

use Nicat\FormFactory\Utilities\FormFactoryTools;
use Nicat\FormFactory\Utilities\Forms\FormInstance;

/**
 * Manages field-rules for forms.
 *
 * Class FieldRuleManager
 * @package Nicat\FormFactory
 */
class FieldRuleManager
{

    /**
     * The FormInstance this FieldRuleManager belongs to.
     *
     * @var FormInstance
     */
    private $form;

    /**
     * Array of rules for fields.
     *
     * @var array
     */
    private $rules = [];

    /**
     * ValueManager constructor.
     *
     * @param FormInstance $form
     */
    public function __construct(FormInstance $form)
    {
        $this->form = $form;
    }

    /**
     * Set rules to be used for all fields.
     * (omit for auto-adoption from request-object, if it is set via the requestObject()-method)
     *
     * @param array $rules
     */
    public function setRules(array $rules)
    {
        foreach ($rules as $fieldName => $fieldRules) {
            $this->rules[$fieldName] = self::parseRules($fieldRules);
        }
    }

    /**
     * Gets the rules of a field of this form.
     *
     * @param string $fieldName
     * @return array
     */
    public function getRulesForField(string $fieldName): array
    {
        $isArray = FormFactoryTools::isArrayField($fieldName);

        // If the name is an array-key (e.g. "domainName[domainLabel]"), we have to convert it into dot-notation to access it's rules
        if ($isArray) {
            $fieldName = FormFactoryTools::convertArrayFieldHtmlName2DotNotation($fieldName);
        }

        // If rules for this field are present in $this->rules, we return them.
        if (isset($this->rules[$fieldName])) {
            return $this->rules[$fieldName];
        }

        // If the field is an array, we also look for a rule defined via wildcard.
        // E.g.: If there are rules defined for "domainList.*.domainName",
        // we must return them, if the current field is called "domainList[0][domainName]"
        if ($isArray) {
            foreach ($this->rules as $ruleField => $ruleSet) {
                if (strpos($ruleField, '.*') !== false) {
                    $ruleFieldRegex = str_replace('.*', '.([0-9]*)', $ruleField);
                    if (preg_match("/^(" . $ruleFieldRegex . ")$/", $fieldName)) {
                        return $this->rules[$ruleField];
                    }
                }
            }
        }

        // If no rules were found, we simply return an empty array.
        return [];
    }

    /**
     * Fetch rules from the requestObject into $this->rules (if no rules were manually set).
     *
     * @param string $requestObjectClassName
     * @throws \Nicat\FormFactory\Exceptions\FormRequestClassNotFoundException
     */
    public function fetchRulesFromRequestObject(string $requestObjectClassName)
    {
        if (count($this->rules) === 0) {
            $requestObjectInstance = FormFactoryTools::initFormRequestObject($requestObjectClassName);
            $this->setRules($requestObjectInstance->rules());
        }
    }

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
                if (is_string($rule)) {
                    $parameters = [];
                    if (str_contains($rule, ':')) {
                        $ruleWithParameters = explode(':', $rule);
                        $rule = $ruleWithParameters[0];
                        $parameters = explode(',', $ruleWithParameters[1]);
                    }
                    $return[$rule] = $parameters;
                }
            }
        }
        return $return;
    }

}
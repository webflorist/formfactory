<?php

namespace Nicat\FormBuilder\Elements;

use Nicat\FormBuilder\FormBuilderTools;

class FormElement extends \Nicat\HtmlBuilder\Elements\FormElement
{

    /**
     * Automatically generate hidden CSRF-token-tag (enabled by default)?
     *
     * @var bool
     */
    protected $generateToken = true;

    /**
     * Display legend regarding mandatory fields?
     *
     * @var bool
     */
    protected $displayMandatoryFieldsLegend = true;

    /**
     * Array of all rules for all fields of this form, that were submitted via the 'rules'-method of this FormElement.
     *
     * @var array
     */
    public $rules = [];

    /**
     * Array of all errors for all fields of this form.
     *
     * @var array
     */
    public $errors = [];

    /**
     * Array of default-values for all fields of this form, that were submitted via the 'values'-method of this FormElement.
     *
     * @var array
     */
    private $defaultValues = [];

    /**
     * Has this form been submitted with the last request?
     *
     * @var bool
     */
    public $wasSubmitted = false;

    /**
     * Overwritten to apply certain modifications.
     *
     * @return string
     */
    public function render(): string
    {
        $this->evaluateSubmittedState();
        $this->appendCSRFToken();
        $this->setDefaultAction();

        $html = parent::render();

        // We remove the closing tag, since FormBuilder closes the form-tag via method close().
        return str_before($html, '</form>');
    }


    /**
     * Evaluates, if this form been submitted via the last request.
     *
     * If there is a submitted field called "_formID", and it's value is the current form-id,
     * this form was indeed submitted during the last request.
     *
     */
    private function evaluateSubmittedState()
    {
        if (\Request::old('_formID') === $this->attributes->getValue('id')) {
            $this->wasSubmitted = true;
        }
    }

    /**
     * Enable / disable automatic generation of hidden CSRF-token-tag.
     * (Enabled by default)
     *
     * @param boolean $generateToken
     * @return $this
     */
    public function generateToken(bool $generateToken = true)
    {
        $this->generateToken = $generateToken;
        return $this;
    }

    /**
     * Append hidden input tag with CSRF-token (except for forms with a GET-method),
     * if $this->>generateToken is not set to false.
     */
    protected function appendCSRFToken()
    {
        if ($this->generateToken && $this->attributes->getValue('method') !== 'get') {
            $this->appendContent(
                (new HiddenInputElement())->name('_token')->value(csrf_token())
            );
        }
    }

    /**
     * Set default action to current URL, if none was set.
     */
    private function setDefaultAction()
    {
        if (!$this->attributes->isSet('action')) {
            $this->action(\URL::current());
        }
    }

    /**
     * Set default-values to be used for all fields.
     *
     * @param array $values
     * @return $this
     */
    public function values(array $values)
    {
        $this->defaultValues = $values;
        return $this;
    }


    /**
     * Gets the default-value of a field stored in this FormElement via the 'values'-method.
     *
     * @param string $fieldName
     * @return string|null
     */
    public function getDefaultValueForField(string $fieldName = '')
    {
        $fieldName = FormBuilderTools::convertArrayFieldHtmlName2DotNotation($fieldName);
        if (array_has($this->defaultValues, $fieldName)) {
            return (array_get($this->defaultValues, $fieldName));
        }
        return null;
    }

    /**
     * Checks, if a default-value of a field was stored in this FormElement via the 'values'-method.
     *
     * @param string $fieldName
     * @return bool
     */
    public function fieldHasDefaultValue(string $fieldName = '')
    {
        $fieldName = FormBuilderTools::convertArrayFieldHtmlName2DotNotation($fieldName);
        if (array_has($this->defaultValues, $fieldName)) {
            return true;
        }
        return false;
    }

    /**
     * Set errors to be used for all fields.
     * (omit for auto-adoption from session)
     *
     * @param array $errors
     * @return $this
     */
    public function errors(array $errors)
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * Gets the error(s) of a field currently stored in the FormBuilder-object.
     *
     * @param string $name
     * @return array
     */
    public function getErrorsForField(string $name): array
    {
        $name = FormBuilderTools::convertArrayFieldHtmlName2DotNotation($name);

        if (isset($this->errors[$name]) > 0) {
            return $this->errors[$name];
        }

        // TODO: look via request-object.

        // If no errors were found, we simply return an empty array.
        return [];
    }


    /**
     * Set rules to be used for all fields.
     * (omit for auto-adoption from request-object, if it is set via the requestObject()-method)
     *
     * @param array $rules
     * @return $this
     */
    public function rules(array $rules)
    {
        foreach ($rules as $fieldName => $fieldRules) {
            $this->rules[$fieldName] = FormBuilderTools::parseRules($fieldRules);
        }
        return $this;
    }

    /**
     * Gets the rules of a field of this form.
     *
     * @param string $name
     * @return array
     */
    public function getRulesForField(string $name): array
    {
        $isArray = FormBuilderTools::isArrayField($name);

        // If the name is an array-key (e.g. "domainName[domainLabel]"), we have to convert it into dot-notation to access it's rules
        if ($isArray) {
            $name = FormBuilderTools::convertArrayFieldHtmlName2DotNotation($name);
        }

        // If rules for this field are present in $this->rules, we return them.
        if (isset($this->rules[$name])) {
            return $this->rules[$name];
        }

        // If the field is an array, we also look for a rule defined via wildcard.
        // E.g.: If there are rules defined for "domainList.*.domainName",
        // we must return them, if the current field is called "domainList[0][domainName]"
        if ($isArray) {
            foreach ($this->rules as $ruleField => $ruleSet) {
                if (strpos($ruleField, '.*') !== false) {
                    $ruleFieldRegex = str_replace('.*', '.([0-9]*)', $ruleField);
                    if (preg_match("/^(" . $ruleFieldRegex . ")$/", $name)) {
                        return $this->rules[$ruleField];
                    }
                }
            }
        }

        // TODO: look via request-object.

        // If no rules were found, we simply return an empty array.
        return [];
    }

}
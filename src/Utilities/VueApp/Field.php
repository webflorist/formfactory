<?php

namespace Nicat\FormFactory\Utilities\VueApp;

use Nicat\FormFactory\Components\FormControls\CheckboxInput;
use Nicat\FormFactory\Components\FormControls\Option;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\FormFactory\Components\FormControls\Select;
use Nicat\FormFactory\Utilities\FormFactoryTools;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\TextareaElement;

/**
 * Object representing a "field" inside the "fields" object of a VueApp.
 *
 * Class VueAppGenerator
 * @package Nicat\FormFactory
 */
class Field
{

    /**
     * The value of this field.
     * This will be used for model-binding.
     *
     * @var bool|string|array
     */
    public $value = '';

    /**
     * Is this field required?
     * 
     * Both the field's 'required' attribute as well as
     * the display of its RequiredFieldIndicator will react to this setting.
     *
     * @var bool
     */
    public $isRequired = false;

    /**
     * Is this field disabled?
     *
     * The field's 'required' attribute will react to this setting.
     *
     * @var bool
     */
    public $isDisabled = false;

    /**
     * Is this field disabled?
     *
     * The field's 'required' attribute will react to this setting.
     *
     * @var bool
     */
    private $fields;

    /**
     * Field constructor.
     * 
     * @param Element $field
     * @param \stdClass $fields
     */
    public function __construct(Element $field, \stdClass $fields)
    {
        $this->fields = $fields;
        $this->value = $this->evaluateFieldValue($field);
        $this->isRequired = ($field->attributes->required === true) ? true : false;
        $this->isDisabled = ($field->attributes->disabled === true) ? true : false;
        $this->fields->{FormFactoryTools::convertArrayFieldHtmlName2JsNotation($field->attributes->name)} = $this;
    }

    /**
     * Evaluates the field's current value.
     *
     * @param Element $field
     * @return bool|mixed|string
     */
    private function evaluateFieldValue(Element $field)
    {
        if ($field->is(CheckboxInput::class)) {
            return ($field->attributes->checked === true);
        }

        if ($field->is(RadioInput::class)) {
            return e($this->evaluateRadioInputValue($field));
        }

        if ($field->is(TextareaElement::class)) {
            /** @var TextareaElement $field */
            return e($field->generateContent());
        }

        if ($field->is(Select::class)) {
            return $this->evaluateSelectValue($field);
        }

        return e($field->attributes->value);

    }

    /**
     * Evaluate current value of a RadioInput.
     *
     * @param RadioInput $radio
     * @return string
     */
    private function evaluateRadioInputValue(RadioInput $radio): string
    {
        $fieldName = $radio->attributes->name;

        // If a valid value for this field-name was already saved from a different radio with the same name, we keep it.
        if (property_exists($this->fields,$fieldName) && (strlen($this->fields->{$fieldName}->value) > 0)) {
            return $this->fields->{$fieldName}->value;
        }

        // Otherwise, if $radio is checked, we return it's "value"-attribute.
        return ($radio->attributes->checked === true) ? $radio->attributes->value : '';
    }

    /**
     * Evaluate current value(s) of a Select.
     *
     * @param Select $field
     * @return array|string
     */
    private function evaluateSelectValue(Select $field)
    {
        $isMultiple = $field->attributes->multiple === true;

        $return = '';
        if ($isMultiple) {
            $return = [];
        }

        foreach ($field->content->getChildrenByClassName(Option::class) as $optionKey => $option) {

            $value = e($option->attributes->value);

            /** @var Option $option */
            if ($option->attributes->isSet('selected')) {
                if (!$isMultiple) {
                    $return = $value;
                    break;
                }
                $return[] = $value;
            }

        }

        return $return;
    }
}
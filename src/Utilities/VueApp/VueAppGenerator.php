<?php

namespace Nicat\FormFactory\Utilities\VueApp;

use Nicat\FormFactory\Components\FormControls\CheckboxInput;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\FormFactory\Components\FormControls\Select;
use Nicat\FormFactory\Utilities\Forms\FormInstance;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\TextareaElement;

/**
 * Generates a Vue-app from a FormInstance.
 *
 * Class VueAppGenerator
 * @package Nicat\FormFactory
 */
class VueAppGenerator
{

    /**
     * The FormInstance to be vueified.
     *
     * @var FormInstance
     */
    private $form;

    /**
     * Content for the fields-data-object.
     *
     * @var []
     */
    private $fields;

    /**
     * FormInstance constructor.
     * @param FormInstance $form
     */
    public function __construct(FormInstance $form)
    {
        $this->form = $form;
        $this->parseFormControls();
    }


    public function generate()
    {

        return "
new Vue({
    el: '#".$this->form->getId()."',
    data: {
        fields : ".$this->convertArrayToJsObject($this->fields)." // TODO: convert from PHP-object
    }
});
        ";
    }

    private function parseFormControls()
    {
        foreach ($this->form->getFormControls() as $control) {
            if ($control->attributes->isSet('name')) {
                $this->fields[$control->attributes->name] = [
                    'value' => $this->evaluateFormControlValue($control),
                    'isRequired' => ($control->attributes->required === true) ? true : false,
                    'isDisabled' => ($control->attributes->disabled === true) ? true : false
                ];
            }
        }
    }

    private function evaluateFormControlValue(Element $control)
    {
        if ($control->is(CheckboxInput::class)) {
            return ($control->attributes->checked === true);
        }

        if ($control->is(RadioInput::class)) {
            return $this->evaluateRadioInputValue($control);
        }

        if ($control->is(TextareaElement::class)) {
            /** @var TextareaElement $control */
            return $control->generateContent();
        }

        if ($control->is(Select::class)) {
            return $this->evaluateSelectValue($control);
        }

        return $control->attributes->value;

    }

    private function convertArrayToJsObject($array)
    {

        $return = "{\n";

        foreach ($array as $key => $value) {

            // Keys are always put within quotes.
            $key = '"' . $key . '"';

            // String-values are also put within quotes.
            if (is_string($value)) {
                $value = '"' . $value . '"';
            }

            // Boolean values are string-ified.
            if (is_bool($value)) {
                $value = ($value) ? 'true': 'false';
            }

            // Array fields are handled recursively.
            if (is_array($value)) {
                $value = $this->convertArrayToJsObject($value);
            }

            $return .= $key.':'.$value.",\n";
        }

        $return .= '}';

        return $return;
    }

    /**
     * @param RadioInput $radio
     * @return string
     */
    private function evaluateRadioInputValue(RadioInput $radio): string
    {
        // If a valid value for this field-name was already saved from a different radio with the same name, we keep it.
        if (isset($this->values[$radio->attributes->name]) && (strlen($this->values[$radio->attributes->name]) > 0)) {
            return $this->values[$radio->attributes->name];
        }

        // Otherwise, if $radio is checked, we return it's "value"-attribute.
        return ($radio->attributes->checked === true) ? $radio->attributes->value : '';
    }

    private function evaluateSelectValue(Element $control)
    {
        // TODO
    }

}
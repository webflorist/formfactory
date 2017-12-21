<?php

namespace Nicat\FormBuilder;

use Nicat\FormBuilder\Components\FieldWrapper;
use Nicat\FormBuilder\Elements\FormElement;
use Nicat\FormBuilder\Elements\NumberInputElement;
use Nicat\FormBuilder\Elements\TextareaElement;
use Nicat\FormBuilder\Elements\TextInputElement;

/**
 * The main class of this package.
 * Provides factory methods for all form-tags.
 *
 * Class FormBuilder
 * @package Nicat\FormBuilder
 *
 *
 */
class FormBuilder
{

    /**
     * The currently open FormElement.
     *
     * @var FormElement
     */
    public $currentForm = null;

    /**
     * Generates and returns the opening form-tag.
     * Also sets the form as $this->currentForm.
     *
     * @param string $id
     * @return FormElement
     */
    public function open(string $id): FormElement
    {
        $formElement = (new FormElement())->id($id);
        $this->currentForm = $formElement;
        return $formElement;
    }

    /**
     * Creates the closing-tag of the form
     *
     * @param bool $showMandatoryFieldsLegend
     * @return string
     */
    public function close(bool $showMandatoryFieldsLegend = true)
    {
        $return = '';
        if ($showMandatoryFieldsLegend && $this->currentForm) {
            $return .= '<div class="text-muted small"><sup>*</sup> ' . trans('Nicat-FormBuilder::formbuilder.mandatoryFields') . '</div>';
        }
        $return .= '</form>';
        $this->currentForm = null;
        return $return;
    }

    /**
     * Generates form-control '<input type="text" />'
     *
     * @param string $name
     * @return TextInputElement
     */
    public function text(string $name): TextInputElement
    {
        return (new TextInputElement())->name($name);
    }

    /**
     * Generates form-control '<textarea></textarea>'
     *
     * @param string $name
     * @return TextareaElement
     */
    public function textarea(string $name): TextareaElement
    {
        return (new TextareaElement())->name($name);
    }

    /**
     * Generates form-control '<input type="number" />'
     *
     * @param string $name
     * @return NumberInputElement
     */
    public function number(string $name): NumberInputElement
    {
        return (new NumberInputElement())->name($name);
    }

}
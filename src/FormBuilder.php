<?php

namespace Nicat\FormBuilder;

use Nicat\FormBuilder\Elements\FormElement;
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
    private $currentForm = null;

    /**
     * Generates and returns the opening form-tag.
     * Also sets the form as $this->current.
     *
     * @param string $id
     * @return FormElement
     */
    public function open(string $id): FormElement
    {
        $formTag = (new FormElement())->id($id);
        $this->currentForm = $formTag;
        return $formTag;
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
     * Generates form-tag '<input type="text" />'
     *
     * @param string $name
     * @return TextInputElement
     */
    public function text(string $name): TextInputElement
    {
        return (new TextInputElement())->name($name);
    }

}
<?php

namespace Nicat\FormFactory\Components\HelpText;

use Nicat\HtmlFactory\Attributes\Traits\AllowsAriaDescribedbyAttribute;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\DivElement;

class HelpTextContainer extends DivElement
{

    /**
     * Array of field-elements, this tag should display errors for.
     *
     * @var AllowsAriaDescribedbyAttribute[]|Element[]|HelpTextInterface[]
     */
    public $helpTextFields = [];

    /**
     * HelpTextContainer constructor.
     *
     * @param Element|null|HelpTextInterface $field
     */
    public function __construct(HelpTextInterface $field = null)
    {
        parent::__construct();
        if (!is_null($field)) {
            $this->addHelpTextField($field);
        }
    }


    /**
     * Adds an element to the list of fields, this tag should display help-texts for.
     *
     * $field must implement HelpTextInterface.
     *
     * @param HelpTextInterface $field
     */
    public function addHelpTextField(HelpTextInterface $field)
    {
        $this->helpTextFields[] = $field;
    }

    /**
     * Gets called before applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function afterDecoration()
    {
        // Establish id of this HelpTextContainer.
        $this->establishId();

        $this->addHelpTexts();
    }

    /**
     * Establishes the ID for this HelpTextContainer.
     */
    private function establishId()
    {
        // If the HelpTextContainer should only display helpTexts for a single field-element,
        // we use the field's ID plus the suffix '_helpText' as this HelpTextContainer's ID.
        if (count($this->helpTextFields) == 1) {
            $this->id($this->helpTextFields[0]->attributes->id . '_helpText');
            return;
        }

        // Otherwise we create a md5-hash of all field-id's and fieldNames this HelpTextContainer should display help-texts for,
        // append the suffix '_helpText' and use it as this HelpTextContainer's ID.
        $fieldIDs = '';
        foreach ($this->helpTextFields as $fieldElement) {
            $fieldIDs .= $fieldElement->attributes->id;
        }
        $this->id(md5($fieldIDs) . '_helpText');

    }

    /**
     * Adds all help-texts for all fields within $this->helpTextFields.
     */
    private function addHelpTexts()
    {

        foreach ($this->helpTextFields as $field) {

            // In case the field has a help-text.
            if ($field->hasHelpText()) {


                $this->addHelpText($field->getHelpText());

                // Add the 'aria-describedby' attribute to the field-element.
                $field->addAriaDescribedby($this->attributes->id);
            }

        }

    }

    /**
     * Adds a help-text this HelpTextContainer's content.
     *
     * @param string $helpText
     */
    private function addHelpText(string $helpText)
    {
        if ($this->content->hasContent()) {
            $helpText = ' ' . $helpText;
        }
        $this->content($helpText);
    }

    /**
     * Renders the element.
     *
     * @return string
     */
    protected function render(): string
    {
        // We only render this HelpTextContainer, if it actually has content.
        if (!$this->content->hasContent()) {
            return '';
        }
        return parent::render();

    }


}
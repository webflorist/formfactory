<?php

namespace Nicat\FormBuilder\Components\Additional;

use Nicat\HtmlBuilder\Elements\Abstracts\Element;
use Nicat\HtmlBuilder\Elements\DivElement;
use Nicat\HtmlBuilder\Elements\LabelElement;

class FieldWrapper extends DivElement
{
    /**
     * The field that should be wrapped.
     *
     * @var Element
     */
    public $field;

    /**
     * The ErrorWrapper, that will display any field-errors.
     *
     * @var ErrorWrapper
     */
    public $errorWrapper;

    /**
     * FieldWrapper constructor.
     *
     * @param Element $field
     */
    public function __construct(Element $field)
    {
        $this->field = $field;
        $this->errorWrapper = new ErrorWrapper();

        parent::__construct();

        $this->data('field-wrapper',true);
    }

    /**
     * Gets called before applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function afterDecoration()
    {
        $this->addHelpText();
        $this->addErrors();
        $this->addLabel();
    }


    private function addLabel()
    {
        if ($this->field->labelMode !== 'none') {

            // Create the label-element with the label-text as it's content.
            $label = (new LabelElement())->content($this->field->label);

            // If labelMode is set to 'bound', we simply wrap the field with the label,
            // and replace the field-element with the label-element in $this.
            if ($this->field->labelMode === 'bound') {
                $this->replaceChild(
                    $this->field,
                    $label->prependChild($this->field)
                );
                return;
            }

            // In all other cases the label-element should have the for-attribute
            // pointing to the field's id.
            $label->for($this->field->attributes->id);

            // If labelMode is set to 'after', we append the label after the field.
            if ($this->field->labelMode === 'after') {
                $this->insertChildAfter(
                    $label,
                    $this->field
                );
                return;
            }

            // If labelMode is set to 'sr-only', we add the 'sr-only' class to the label.
            if ($this->field->labelMode === 'sr-only') {
                $label->addClass('sr-only');
            }

            // Standard-procedure is to insert the label before the field.
            $this->prependChild(
                $label
            );

        }
    }

    private function addHelpText()
    {
        if ($this->field->hasHelpText()) {

            // The ID of the HelpTextElement should be the id of the field plus the suffix '_helpText'.
            $helpTextElementId = $this->field->attributes->id . '_helpText';

            // Create HelpTextElement.
            $helpTextElement = (new HelpText())
                ->content($this->field->getHelpText())
                ->id($helpTextElementId);

            // Add the help-text-element according to it's desired location.
            if ($this->field->helpTextLocation === 'append') {
                $this->field->appendChild($helpTextElement);
            }
            if ($this->field->helpTextLocation === 'prepend') {
                $this->field->prependChild($helpTextElement);
            }
            if ($this->field->helpTextLocation === 'after') {
                $this->insertChildAfter(
                    $helpTextElement,
                    $this->field
                );
            }
            if ($this->field->helpTextLocation === 'before') {
                $this->insertChildBefore(
                    $helpTextElement,
                    $this->field
                );
            }

            // Add the 'aria-describedby' attribute to the field-element.
            $this->field->addAriaDescribedby($helpTextElementId);
        }
    }

    private function addErrors()
    {
        if ($this->field->showErrors) {

            $this->errorWrapper->addErrorField($this->field);

            // Add the errorWrapper according to the desired location.
            if ($this->field->errorsLocation === 'append') {
                $this->field->appendChild($this->errorWrapper);
            }
            if ($this->field->errorsLocation === 'prepend') {
                $this->field->prependChild($this->errorWrapper);
            }
            if ($this->field->errorsLocation === 'after') {
                $this->insertChildAfter(
                    $this->errorWrapper,
                    $this->field
                );
            }
            if ($this->field->errorsLocation === 'before') {
                $this->insertChildBefore(
                    $this->errorWrapper,
                    $this->field
                );
            }

        }
    }


}
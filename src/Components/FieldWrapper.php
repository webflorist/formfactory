<?php

namespace Nicat\FormBuilder\Components;

use Nicat\FormBuilder\Elements\HelpTextElement;
use Nicat\HtmlBuilder\Components\AlertComponent;
use Nicat\HtmlBuilder\Elements\Abstracts\Element;
use Nicat\HtmlBuilder\Elements\DivElement;
use Nicat\HtmlBuilder\Elements\LabelElement;

class FieldWrapper extends DivElement
{
    public $field;

    public function __construct(Element $field)
    {
        $this->field = $field;

        parent::__construct();
    }

    public function generate()
    {
        $this->addHelpText();
        $this->addErrors();
        $this->addLabel();

        return parent::generate();
    }

    private function addLabel()
    {
        if ($this->field->labelMode !== 'none') {
            $label = (new LabelElement())
                ->for($this->field->attributes->getValue('id'))
                ->content($this->field->label);

            if ($this->field->labelMode === 'sr-only') {
                $label->addClass('sr-only');
            }

            $this->prependContent(
                $label
            );
        }
    }

    private function addHelpText()
    {
        if ($this->field->hasHelpText()) {

            // The ID of the HelpTextElement should be the id of the field plus the suffix '_helpText'.
            $helpTextElementId = $this->field->attributes->getValue('id') . '_helpText';

            // Create HelpTextElement.
            $helpTextElement = (new HelpTextElement())
                ->content($this->field->getHelpText())
                ->id($helpTextElementId);

            // Add the help-text-element according to it's desired location.
            if ($this->field->helpTextLocation === 'append') {
                $this->field->appendContent($helpTextElement);
            }
            if ($this->field->helpTextLocation === 'prepend') {
                $this->field->prependContent($helpTextElement);
            }
            if ($this->field->helpTextLocation === 'after') {
                $this->appendContent($helpTextElement);
            }
            if ($this->field->helpTextLocation === 'before') {
                $this->prependContent($helpTextElement);
            }

            // Add the 'aria-describedby' attribute to the field-element.
            $this->field->addAriaDescribedby($helpTextElementId);
        }
    }

    private function addErrors()
    {
        if ($this->field->hasErrors() && $this->field->showErrors) {

            // The ID of the error-container should be the id of the field plus the suffix '_errors'.
            $errorContainerId = $this->field->attributes->getValue('id') . '_errors';

            // Create error-container.
            $errorContainer = (new AlertComponent('danger'))
                ->id($errorContainerId);

            // Put each individual error inside a div and set it as content of $errorContainer.
            foreach ($this->field->getErrors() as $errorMsg) {
                $errorContainer->content((new DivElement())->content($errorMsg));
            }

            // Add the help-text-element according to it's desired location.
            if ($this->field->errorsLocation === 'append') {
                $this->field->appendContent($errorContainer);
            }
            if ($this->field->errorsLocation === 'prepend') {
                $this->field->prependContent($errorContainer);
            }
            if ($this->field->errorsLocation === 'after') {
                $this->appendContent($errorContainer);
            }
            if ($this->field->errorsLocation === 'before') {
                $this->prependContent($errorContainer);
            }

            // Add the 'aria-describedby' attribute to the field-element.
            $this->field->addAriaDescribedby($errorContainerId);

            // Add the 'aria-invalid' attribute to the field-element.
            $this->field->ariaInvalid();

            // Add error-class to wrapper.
            // TODO: make adjustable via decorators.
            $this->addClass('has-error');
        }
    }


}
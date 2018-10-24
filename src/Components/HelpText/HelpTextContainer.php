<?php

namespace Nicat\FormFactory\Components\HelpText;

use Nicat\HtmlFactory\Attributes\Traits\AllowsAriaDescribedbyAttribute;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\DivElement;

class HelpTextContainer extends DivElement
{

    /**
     * Array of field-elements, this tag should display help-text for.
     *
     * @var AllowsAriaDescribedbyAttribute|Element|HelpTextInterface
     */
    public $field;

    /**
     * HelpTextContainer constructor.
     *
     * @param HelpTextInterface $field
     */
    public function __construct(HelpTextInterface $field)
    {
        parent::__construct();

        $this->field = $field;

        if ($this->field->hasHelpText()) {

            // Set field's helpText as this container's content.
            $this->content($this->field->getHelpText());
            $this->id($this->field->attributes->id . '_helpText');
            $this->field->addAriaDescribedby($this->attributes->id);
        }
    }

    /**
     * Renders the element.
     *
     * @return string
     */
    protected function render(): string
    {
        // We only render this HelpTextContainer, if the field actually has a help-text.
        if (!$this->field->hasHelpText()) {
            return '';
        }
        return parent::render();

    }

}
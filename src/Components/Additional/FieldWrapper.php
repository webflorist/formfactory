<?php

namespace Nicat\FormFactory\Components\Additional;

use Nicat\FormFactory\Components\HelpText\HelpTextContainer;
use Nicat\FormFactory\Components\HelpText\HelpTextInterface;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\DivElement;

class FieldWrapper extends DivElement
{
    /**
     * The field that should be wrapped.
     *
     * @var HelpTextInterface|Element|null
     */
    public $field;

    /**
     * Should this FieldWrapper display errors?
     *
     * @var bool
     */
    public $displayErrors = true;

    /**
     * Should this FieldWrapper display a HelpText?
     *
     * @var bool
     */
    public $displayHelpText = true;

    /**
     * Should this FieldWrapper display a label?
     *
     * @var bool
     */
    public $displayLabel = true;

    /**
     * FieldWrapper constructor.
     *
     * @param Element $field
     */
    public function __construct(Element $field=null)
    {
        parent::__construct();

        $this->field = $field;

        $this->data('field-wrapper', true);
    }

    /**
     * Gets called before applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function afterDecoration()
    {
        if ($this->displayErrors) {
            $this->addErrors();
        }
        if ($this->displayLabel) {
            $this->addLabel();
        }
        if ($this->displayHelpText) {
            $this->addHelpText();
        }
    }

    private function addLabel()
    {

        // If labelMode is set to 'bound', we simply wrap the field with the label,
        // and replace the field-element with the label-element in $this.
        if (!is_null($this->field) && $this->field->hasLabel() && $this->field->labelMode === 'bound') {
            $this->content->replaceChild(
                $this->field,
                $this->field->label->content($this->field)
            );
            return;
        }

        // Standard-procedure is simply prepend the label.
        $this->prependContent($this->field->label);
    }

    private function addHelpText()
    {
        $this->appendContent($this->field->helpTextContainer);
    }

    private function addErrors()
    {
        $this->prependContent($this->field->errorContainer);
    }


}
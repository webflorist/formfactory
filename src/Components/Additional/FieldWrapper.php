<?php

namespace Nicat\FormFactory\Components\Additional;

use Nicat\FormFactory\Components\HelpText\HelpTextContainer;
use Nicat\FormFactory\Components\HelpText\HelpTextInterface;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\DivElement;
use Nicat\HtmlFactory\Elements\LabelElement;

class FieldWrapper extends DivElement
{
    /**
     * The field that should be wrapped.
     *
     * @var HelpTextInterface|Element|null
     */
    public $field;

    /**
     * The ErrorContainer, that will display any field-errors.
     *
     * @var ErrorContainer
     */
    public $errorContainer;

    /**
     * The HelpTextContainer, that will display any help-texts.
     *
     * @var ErrorContainer
     */
    public $helpTextContainer;

    /**
     * The FieldLabel for this field.
     *
     * @var FieldLabel
     */
    public $label;

    /**
     * FieldWrapper constructor.
     *
     * @param Element $field
     */
    public function __construct(Element $field=null)
    {
        parent::__construct();

        $this->field = $field;
        $this->errorContainer = new ErrorContainer($field);
        $this->helpTextContainer = new HelpTextContainer($field);
        $this->label = new FieldLabel($field);

        $this->data('field-wrapper', true);
    }

    /**
     * Gets called before applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function afterDecoration()
    {
        $this->addErrors();
        $this->addLabel();
        $this->addHelpText();
    }

    private function addLabel()
    {

        // If labelMode is set to 'bound', we simply wrap the field with the label,
        // and replace the field-element with the label-element in $this.
        if (!is_null($this->field) && $this->field->labelMode === 'bound') {
            $this->content->replaceChild(
                $this->field,
                $this->label->content($this->field)
            );
            return;
        }

        // Standard-procedure is simply prepend the label.
        $this->prependContent($this->label);
    }

    private function addHelpText()
    {
        $this->appendContent($this->helpTextContainer);
    }

    private function addErrors()
    {
        $this->prependContent($this->errorContainer);
    }


}
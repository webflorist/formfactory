<?php

namespace Nicat\FormFactory\Components\Additional;

use Nicat\FormFactory\Components\FormControls\CheckboxInput;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\LabelElement;

class FieldLabel extends LabelElement
{

    /**
     * The field this FieldLabel belongs to.
     *
     * @var Element
     */
    public $field;

    /**
     * FieldLabel constructor.
     *
     * @param Element|null $field
     */
    public function __construct($field = null)
    {
        parent::__construct();
        $this->field = $field;
    }

    /**
     * Gets called after applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function afterDecoration()
    {
        if (!is_null($this->field) && $this->field->labelMode !== 'none') {

            // For fields other than Radio and Checkbox, we add the 'for'-attribute.
            if (!$this->field->is(RadioInput::class) && !$this->field->is(CheckboxInput::class)) {
                $this->for($this->field->attributes->id);
            }

            // Set the field's label-text as content.
            $this->content($this->field->label);

            // Set the 'sr-only' class, if field's label-mode says.
            if ($this->field->labelMode === 'sr-only') {
                $this->addClass('sr-only');
            }
        }
    }
    /**
     * Renders the element.
     *
     * @return string
     */
    protected function render(): string
    {
        // We only render this FieldLabel, if it actually has content.
        if (!$this->content->hasContent()) {
            return '';
        }
        return parent::render();

    }


}
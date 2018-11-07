<?php

namespace Nicat\FormFactory\Components\Helpers;

use Nicat\FormFactory\Components\Contracts\FieldInterface;
use Nicat\FormFactory\Components\Contracts\FormControlInterface;
use Nicat\FormFactory\Components\FormControls\TextInput;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\SupElement;

class RequiredFieldIndicator extends SupElement
{
    /**
     * The field this FieldLabel belongs to.
     *
     * @var Element|FieldInterface|FormControlInterface
     */
    public $field;

    /**
     * RequiredFieldIndicator constructor.
     *
     * @param FieldInterface|string $field
     */
    public function __construct($field)
    {

        // If we just get a field-name, we create a temporary text-input from it,
        // since a FieldInterface is required for further processing.
        if (is_string($field)) {
            $field = new TextInput($field);
        }

        $this->field = $field;
        parent::__construct();
    }

    protected function setUp()
    {
        $this->content('*');

        if ($this->field->isVueEnabled()) {
            $this->vIf( "fields['".$this->field->getFieldName()."'].isRequired");
        }
    }

    /**
     * Manipulate the generated HTML.
     *
     * @param string $output
     */
    protected function manipulateOutput(string &$output)
    {

        // Do not generate output, if field is not required, and vue is not used.
        if (!$this->field->attributes->required && !$this->field->isVueEnabled()) {
            $output = '';
        }

        if ($this->field->isVueEnabled()) {
            $output = "<template>$output</template>";
        }
    }

}
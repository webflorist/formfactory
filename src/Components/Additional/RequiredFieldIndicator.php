<?php

namespace Nicat\FormFactory\Components\Additional;

use Nicat\FormFactory\Components\Contracts\FieldInterface;
use Nicat\FormFactory\Components\Contracts\FormControlInterface;
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
     * @param FieldInterface $field
     */
    public function __construct(FieldInterface $field)
    {
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
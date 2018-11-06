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
    }

    /**
     * Manipulate the generated HTML.
     *
     * @param string $output
     */
    protected function manipulateOutput(string &$output)
    {
        if ($this->field->isVueEnabled()) {
            $output = "<template>$output</template>";
        }
    }

}
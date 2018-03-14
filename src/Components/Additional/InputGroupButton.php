<?php

namespace Nicat\FormBuilder\Components\Additional;

use Nicat\HtmlBuilder\Elements\ButtonElement;
use Nicat\HtmlBuilder\Elements\DivElement;

class InputGroupButton extends DivElement
{

    /**
     * InputGroup constructor.
     *
     * @param ButtonElement $button
     */
    public function __construct(ButtonElement $button)
    {
        parent::__construct();

        $this->addClass('input-group-btn');
        $this->appendChild($button);
    }

}
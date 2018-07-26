<?php

namespace Nicat\FormFactory\Components\Additional;

use Nicat\HtmlFactory\Elements\ButtonElement;
use Nicat\HtmlFactory\Elements\DivElement;

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
        $this->appendContent($button);
    }

}
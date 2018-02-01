<?php

namespace Nicat\FormBuilder\Components;

use Nicat\HtmlBuilder\Elements\ButtonElement;
use Nicat\HtmlBuilder\Elements\DivElement;

class InputGroupButtonComponent extends DivElement
{

    /**
     * InputGroupComponent constructor.
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
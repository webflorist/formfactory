<?php

namespace Webflorist\FormFactory\Components\Additional;

use Webflorist\HtmlFactory\Elements\ButtonElement;
use Webflorist\HtmlFactory\Elements\DivElement;

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
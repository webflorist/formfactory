<?php

namespace Nicat\FormFactory\Components\FormControls;

use Nicat\HtmlFactory\Elements\ButtonElement;
use Nicat\HtmlFactory\Elements\DivElement;

class ButtonGroup extends DivElement
{
    /**
     * ButtonGroup constructor.
     *
     * @param ButtonElement[] $buttons
     */
    public function __construct($buttons)
    {
        parent::__construct();
        $this->content($buttons);
        $this->addRole('group');
    }

}
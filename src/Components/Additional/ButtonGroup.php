<?php

namespace Nicat\FormFactory\Components\Additional;

use Nicat\HtmlFactory\Elements\DivElement;

class ButtonGroup extends DivElement
{
    /**
     * ButtonGroup constructor.
     *
     * @param array $buttons
     */
    public function __construct($buttons=[])
    {
        parent::__construct();
        $this->content($buttons);
        $this->addRole('group');
    }

}
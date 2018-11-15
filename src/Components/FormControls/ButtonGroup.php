<?php

namespace Webflorist\FormFactory\Components\FormControls;

use Webflorist\HtmlFactory\Elements\ButtonElement;
use Webflorist\HtmlFactory\Elements\DivElement;

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
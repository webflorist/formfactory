<?php

namespace Nicat\FormBuilder\Components;

use Nicat\HtmlBuilder\Elements\DivElement;

class ButtonGroupComponent extends DivElement
{
    /**
     * ButtonGroupComponent constructor.
     * @param array $buttons
     */
    public function __construct($buttons=[])
    {
        parent::__construct();
        $this->content($buttons);
        $this->addRole('group');
    }

}
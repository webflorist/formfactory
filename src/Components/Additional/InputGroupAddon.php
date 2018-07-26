<?php

namespace Nicat\FormFactory\Components\Additional;

use Nicat\FormFactory\Components\FormControls\CheckboxInput;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\HtmlFactory\Elements\DivElement;

class InputGroupAddon extends DivElement
{

    /**
     * InputGroupAddon constructor.
     *
     * @param string|CheckboxInput|RadioInput $content
     */
    public function __construct($content)
    {
        parent::__construct();

        if (is_a($content,CheckboxInput::class) || is_a($content,RadioInput::class)) {
            $content->wrap(false);
        }

        $this->addClass('input-group-addon');
        $this->content($content);
    }

}
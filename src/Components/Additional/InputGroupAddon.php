<?php

namespace Webflorist\FormFactory\Components\Additional;

use Webflorist\FormFactory\Components\FormControls\CheckboxInput;
use Webflorist\FormFactory\Components\FormControls\RadioInput;
use Webflorist\HtmlFactory\Elements\DivElement;

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
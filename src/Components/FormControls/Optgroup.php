<?php

namespace Nicat\FormFactory\Components\FormControls;

use Nicat\HtmlFactory\Elements\OptgroupElement;

class Optgroup extends OptgroupElement
{

    /**
     * Optgroup constructor.
     *
     * @param string $label
     * @param array $options
     */
    public function __construct(string $label, array $options)
    {
        parent::__construct();
        $this->label($label);
        $this->content($options);
    }

}
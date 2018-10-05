<?php

namespace Nicat\FormFactory\Components\FormControls;

use Nicat\FormFactory\Components\Traits\CanBelongToFormInstance;
use Nicat\HtmlFactory\Elements\OptgroupElement;

class Optgroup extends OptgroupElement
{

    use CanBelongToFormInstance;

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
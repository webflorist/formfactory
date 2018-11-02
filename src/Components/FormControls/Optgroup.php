<?php

namespace Nicat\FormFactory\Components\FormControls;

use Nicat\FormFactory\Components\Traits\FormControlTrait;
use Nicat\FormFactory\Components\Contracts\FormControlInterface;
use Nicat\HtmlFactory\Elements\OptgroupElement;

class Optgroup
    extends OptgroupElement
    implements FormControlInterface
{

    use FormControlTrait;

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
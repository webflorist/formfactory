<?php

namespace Nicat\FormBuilder\Components;

use Nicat\FormBuilder\Components\Contracts\DynamicListTemplateInterface;
use Nicat\FormBuilder\Elements\ButtonElement;
use Nicat\FormBuilder\Elements\CheckboxInputElement;
use Nicat\FormBuilder\Elements\RadioInputElement;
use Nicat\HtmlBuilder\Elements\Abstracts\Element;
use Nicat\HtmlBuilder\Elements\DivElement;

class InputGroupAddonComponent extends DivElement
{

    /**
     * InputGroupAddonComponent constructor.
     *
     * @param string|CheckboxInputElement|RadioInputElement $content
     */
    public function __construct($content)
    {
        parent::__construct();

        if (is_a($content,CheckboxInputElement::class) || is_a($content,RadioInputElement::class)) {
            $content->wrap(false);
        }

        $this->addClass('input-group-addon');
        $this->content($content);
    }

}
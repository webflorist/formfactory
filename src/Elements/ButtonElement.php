<?php

namespace Nicat\FormBuilder\Elements;

use Nicat\FormBuilder\Elements\Traits\UsesAutoTranslation;
use Nicat\HtmlBuilder\Components\Traits\HasContext;

class ButtonElement extends \Nicat\HtmlBuilder\Elements\ButtonElement
{
    use UsesAutoTranslation,
        HasContext;
}
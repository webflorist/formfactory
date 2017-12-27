<?php

namespace Nicat\FormBuilder\Elements;

use Nicat\FormBuilder\Elements\Traits\UsesAutoTranslation;
use Nicat\HtmlBuilder\Components\Traits\HasContext;

class ResetButtonElement extends \Nicat\HtmlBuilder\Elements\ResetButtonElement
{
    use UsesAutoTranslation,
        HasContext;
}
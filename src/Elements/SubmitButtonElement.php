<?php

namespace Nicat\FormBuilder\Elements;

use Nicat\FormBuilder\Elements\Traits\UsesAutoTranslation;
use Nicat\HtmlBuilder\Components\Traits\HasContext;

class SubmitButtonElement extends \Nicat\HtmlBuilder\Elements\SubmitButtonElement
{
    use UsesAutoTranslation,
        HasContext;
}
<?php

namespace Nicat\FormBuilder\Elements;

use Nicat\FormBuilder\Elements\Traits\CanHaveErrors;

class HiddenInputElement extends \Nicat\HtmlBuilder\Elements\HiddenInputElement
{
    use CanHaveErrors;
}
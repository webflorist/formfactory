<?php

namespace Nicat\FormBuilder\Elements;

use Nicat\FormBuilder\Elements\Traits\CanAutoSubmit;
use Nicat\FormBuilder\Elements\Traits\CanHaveErrors;
use Nicat\FormBuilder\Elements\Traits\CanHaveHelpText;
use Nicat\FormBuilder\Elements\Traits\CanHaveLabel;
use Nicat\FormBuilder\Elements\Traits\CanHaveRules;
use Nicat\FormBuilder\Elements\Traits\CanPerformAjaxValidation;
use Nicat\FormBuilder\Elements\Traits\UsesAutoTranslation;

class DateInputElement extends \Nicat\HtmlBuilder\Elements\DateInputElement
{
    use CanHaveLabel,
        CanHaveRules,
        CanHaveHelpText,
        UsesAutoTranslation,
        CanHaveErrors,
        CanAutoSubmit,
        CanPerformAjaxValidation;

}
<?php

namespace Nicat\FormBuilder\Elements;

use Nicat\FormBuilder\Elements\Traits\CanAutoSubmit;
use Nicat\FormBuilder\Elements\Traits\CanHaveErrors;
use Nicat\FormBuilder\Elements\Traits\CanHaveHelpText;
use Nicat\FormBuilder\Elements\Traits\CanHaveLabel;
use Nicat\FormBuilder\Elements\Traits\CanHaveRules;
use Nicat\FormBuilder\Elements\Traits\CanPerformAjaxValidation;
use Nicat\FormBuilder\Elements\Traits\UsesAutoTranslation;

class DatetimeLocalInputElement extends \Nicat\HtmlBuilder\Elements\DatetimeLocalInputElement
{
    use CanHaveLabel,
        CanHaveRules,
        CanHaveHelpText,
        UsesAutoTranslation,
        CanHaveErrors,
        CanAutoSubmit,
        CanPerformAjaxValidation;

}
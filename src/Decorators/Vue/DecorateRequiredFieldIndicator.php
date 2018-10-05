<?php

namespace Nicat\FormFactory\Decorators\Vue;

use Nicat\FormFactory\Components\Additional\FieldWrapper;
use Nicat\FormFactory\Components\Additional\RequiredFieldIndicator;
use Nicat\FormFactory\Components\FormControls\MonthInput;
use Nicat\FormFactory\Components\FormControls\PasswordInput;
use Nicat\FormFactory\Components\FormControls\RangeInput;
use Nicat\FormFactory\Components\FormControls\SearchInput;
use Nicat\FormFactory\Components\FormControls\TelInput;
use Nicat\FormFactory\Components\FormControls\TimeInput;
use Nicat\FormFactory\Components\FormControls\UrlInput;
use Nicat\FormFactory\Components\FormControls\WeekInput;
use Nicat\FormFactory\FormFactory;
use Nicat\FormFactory\Utilities\FieldRules\FieldRuleProcessor;
use Nicat\FormFactory\Utilities\FieldValues\FieldValueProcessor;
use Nicat\FormFactory\Components\FormControls\CheckboxInput;
use Nicat\FormFactory\Components\FormControls\ColorInput;
use Nicat\FormFactory\Components\FormControls\DateInput;
use Nicat\FormFactory\Components\FormControls\DatetimeInput;
use Nicat\FormFactory\Components\FormControls\DatetimeLocalInput;
use Nicat\FormFactory\Components\FormControls\EmailInput;
use Nicat\FormFactory\Components\FormControls\FileInput;
use Nicat\FormFactory\Components\FormControls\HiddenInput;
use Nicat\FormFactory\Components\FormControls\NumberInput;
use Nicat\FormFactory\Components\FormControls\Option;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\FormFactory\Components\FormControls\Select;
use Nicat\FormFactory\Components\FormControls\Textarea;
use Nicat\FormFactory\Components\FormControls\TextInput;
use Nicat\FormFactory\Components\Traits\CanHaveHelpText;
use Nicat\FormFactory\Components\Traits\CanHaveLabel;
use Nicat\FormFactory\Components\Traits\UsesAutoTranslation;
use Nicat\FormFactory\Utilities\FormFactoryTools;
use Nicat\HtmlFactory\Attributes\Traits\AllowsVueModelDirective;
use Nicat\HtmlFactory\Decorators\Abstracts\Decorator;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Attributes\Traits\AllowsPlaceholderAttribute;

/**
 * Apply various decorations to FormFactory-fields.
 *
 * Class DecorateFields
 * @package Nicat\FormFactory\Decorators\General
 */
class DecorateRequiredFieldIndicator extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var Element|RequiredFieldIndicator
     */
    protected $element;

    /**
     * Returns the group-ID of this decorator.
     *
     * Returning null means this decorator will always be applied.
     *
     * @return string|null
     */
    public static function getGroupId()
    {
        return null;
    }

    /**
     * Returns an array of class-names of elements, that should be decorated by this decorator.
     *
     * @return string[]
     */
    public static function getSupportedElements(): array
    {
        return [
            RequiredFieldIndicator::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        if (!is_null($this->element->field)) {
            $this->element->vIf('fields.'.FormFactoryTools::convertArrayFieldHtmlName2JsNotation($this->element->field->attributes->name).'.isRequired');
        }

    }

}
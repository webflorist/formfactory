<?php

namespace Nicat\FormFactory\Decorators\Vue;

use Nicat\FormFactory\Components\Additional\FieldWrapper;
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
class DecorateFields extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var Element|AllowsVueModelDirective
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
        return 'vue:v2';
    }

    /**
     * Returns an array of class-names of elements, that should be decorated by this decorator.
     *
     * @return string[]
     */
    public static function getSupportedElements(): array
    {
        return [
            CheckboxInput::class,
            ColorInput::class,
            DateInput::class,
            DatetimeInput::class,
            DatetimeLocalInput::class,
            EmailInput::class,
            FileInput::class,
            HiddenInput::class,
            MonthInput::class,
            NumberInput::class,
            PasswordInput::class,
            RadioInput::class,
            RangeInput::class,
            SearchInput::class,
            Select::class,
            TelInput::class,
            Textarea::class,
            TextInput::class,
            TimeInput::class,
            UrlInput::class,
            WeekInput::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        $fieldBase = 'fields.'.$this->element->attributes->name;
        if (!$this->element->attributes->isSet('v-model')) {
            $this->element->vModel($fieldBase.'.value');
        }
        if (!$this->element->attributes->isSet('v-bind')) {
            $this->element->vBind(null,
                '{ required: '.$fieldBase.'.isRequired, disabled: '.$fieldBase.'.isDisabled }'
            );
        }

    }

}
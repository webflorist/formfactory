<?php

namespace Nicat\FormFactory\Decorators\General;

use Nicat\FormFactory\Components\Additional\FieldWrapper;
use Nicat\FormFactory\Components\Additional\RadioGroup;
use Nicat\FormFactory\Components\Form;
use Nicat\FormFactory\Components\FormControls\MonthInput;
use Nicat\FormFactory\Components\FormControls\PasswordInput;
use Nicat\FormFactory\Components\FormControls\RangeInput;
use Nicat\FormFactory\Components\FormControls\SearchInput;
use Nicat\FormFactory\Components\FormControls\TelInput;
use Nicat\FormFactory\Components\FormControls\TimeInput;
use Nicat\FormFactory\Components\FormControls\UrlInput;
use Nicat\FormFactory\Components\FormControls\WeekInput;
use Nicat\FormFactory\Components\FormControls\CheckboxInput;
use Nicat\FormFactory\Components\FormControls\ColorInput;
use Nicat\FormFactory\Components\FormControls\DateInput;
use Nicat\FormFactory\Components\FormControls\DatetimeInput;
use Nicat\FormFactory\Components\FormControls\DatetimeLocalInput;
use Nicat\FormFactory\Components\FormControls\EmailInput;
use Nicat\FormFactory\Components\FormControls\FileInput;
use Nicat\FormFactory\Components\FormControls\HiddenInput;
use Nicat\FormFactory\Components\FormControls\NumberInput;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\FormFactory\Components\FormControls\Select;
use Nicat\FormFactory\Components\FormControls\Textarea;
use Nicat\FormFactory\Components\FormControls\TextInput;
use Nicat\FormFactory\Utilities\ComponentLists;
use Nicat\HtmlFactory\Decorators\Abstracts\Decorator;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\FormFactory\Components\FormControls\Button;
use Nicat\FormFactory\Components\FormControls\ResetButton;
use Nicat\FormFactory\Components\FormControls\SubmitButton;

/**
 * Apply various decorations to FormFactory-fields.
 *
 * Class DecorateFields
 * @package Nicat\FormFactory\Decorators\General
 */
class ApplyViews extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var Element
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
        return array_merge(
            ComponentLists::fields(),
            [
                Form::class,
                RadioGroup::class
            ]
        );
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        if (config('formfactory.views.enabled') && is_null($this->element->view)) {
            try {
                $this->element->view(config('formfactory.views.base') . '.' . kebab_case((new \ReflectionClass($this->element))->getShortName()));
            } catch (\ReflectionException $e) {
            }
        }

    }
}
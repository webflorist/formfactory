<?php

namespace Nicat\FormFactory\Utilities;

use Nicat\FormFactory\Components\FormControls\Button;
use Nicat\FormFactory\Components\FormControls\HiddenInput;
use Nicat\FormFactory\Components\FormControls\Optgroup;
use Nicat\FormFactory\Components\FormControls\Option;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\FormFactory\Components\FormControls\MonthInput;
use Nicat\FormFactory\Components\FormControls\PasswordInput;
use Nicat\FormFactory\Components\FormControls\RangeInput;
use Nicat\FormFactory\Components\FormControls\ResetButton;
use Nicat\FormFactory\Components\FormControls\SearchInput;
use Nicat\FormFactory\Components\FormControls\SubmitButton;
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
use Nicat\FormFactory\Components\FormControls\NumberInput;
use Nicat\FormFactory\Components\FormControls\Select;
use Nicat\FormFactory\Components\FormControls\Textarea;
use Nicat\FormFactory\Components\FormControls\TextInput;

/**
 * This class provides some static functions
 * that return various arrays of component/element-classes.
 *
 * Class FormFactory
 * @package Nicat\FormFactory
 *
 */
class ComponentLists
{

    /**
     * Returns all <button> form controls.
     *
     * @return array
     */
    public static function buttons() : array
    {
        return [
            Button::class,
            ResetButton::class,
            SubmitButton::class
        ];
    }

    /**
     * Returns all <input> form controls.
     *
     * @return array
     */
    public static function inputs() : array
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
            TelInput::class,
            TextInput::class,
            TimeInput::class,
            UrlInput::class,
            WeekInput::class
        ];
    }

    /**
     * Returns all form controls.
     * (<input>, <button>, <select>, <textarea>, <optgroup>, <option>)
     *
     * @return array
     */
    public static function formControls() : array
    {
        return array_merge(
            self::buttons(),
            self::inputs(),
            [
                Optgroup::class,
                Option::class,
                Select::class,
                Textarea::class,
            ]
        );
    }

    /**
     * Return all fields (= form controls, that have a 'name' attribute and are not buttons).
     * (<input>, <select>, <textarea>)
     *
     * @return array
     */
    public static function fields() : array
    {
        return array_merge(
            self::inputs(),
            [
                Select::class,
                Textarea::class,
            ]
        );
    }

    /**
     * Returns all labelable fields (= all fields except <input type=hidden>).
     *
     * @return array
     */
    public static function labelableFields() : array
    {
        $fields = self::fields();
        unset($fields[array_search(HiddenInput::class, $fields)]);
        return $fields;
    }

}
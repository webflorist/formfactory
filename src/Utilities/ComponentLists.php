<?php

namespace Webflorist\FormFactory\Utilities;

use Webflorist\FormFactory\Components\FormControls\Button;
use Webflorist\FormFactory\Components\FormControls\HiddenInput;
use Webflorist\FormFactory\Components\FormControls\Optgroup;
use Webflorist\FormFactory\Components\FormControls\Option;
use Webflorist\FormFactory\Components\FormControls\RadioInput;
use Webflorist\FormFactory\Components\FormControls\MonthInput;
use Webflorist\FormFactory\Components\FormControls\PasswordInput;
use Webflorist\FormFactory\Components\FormControls\RangeInput;
use Webflorist\FormFactory\Components\FormControls\ResetButton;
use Webflorist\FormFactory\Components\FormControls\SearchInput;
use Webflorist\FormFactory\Components\FormControls\SubmitButton;
use Webflorist\FormFactory\Components\FormControls\TelInput;
use Webflorist\FormFactory\Components\FormControls\TimeInput;
use Webflorist\FormFactory\Components\FormControls\UrlInput;
use Webflorist\FormFactory\Components\FormControls\WeekInput;
use Webflorist\FormFactory\Components\FormControls\CheckboxInput;
use Webflorist\FormFactory\Components\FormControls\ColorInput;
use Webflorist\FormFactory\Components\FormControls\DateInput;
use Webflorist\FormFactory\Components\FormControls\DatetimeInput;
use Webflorist\FormFactory\Components\FormControls\DatetimeLocalInput;
use Webflorist\FormFactory\Components\FormControls\EmailInput;
use Webflorist\FormFactory\Components\FormControls\FileInput;
use Webflorist\FormFactory\Components\FormControls\NumberInput;
use Webflorist\FormFactory\Components\FormControls\Select;
use Webflorist\FormFactory\Components\FormControls\Textarea;
use Webflorist\FormFactory\Components\FormControls\TextInput;

/**
 * This class provides some static functions
 * that return various arrays of component/element-classes.
 *
 * Class FormFactory
 * @package Webflorist\FormFactory
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
<?php

namespace Nicat\FormFactory\Components\FormControls;

use Nicat\FormFactory\Components\Traits\FieldTrait;
use Nicat\FormFactory\Components\Contracts\FieldInterface;
use Nicat\FormFactory\Components\Traits\FormControlTrait;
use Nicat\FormFactory\Components\Contracts\FormControlInterface;
use Nicat\FormFactory\Components\Traits\HelpTextTrait;
use Nicat\FormFactory\Components\Contracts\HelpTextInterface;
use Nicat\FormFactory\Components\Traits\LabelTrait;
use Nicat\FormFactory\Components\Contracts\LabelInterface;
use Nicat\FormFactory\Components\Contracts\AutoTranslationInterface;
use Nicat\FormFactory\Components\Traits\AutoTranslationTrait;
use Nicat\HtmlFactory\Components\RadioInputComponent;

class RadioInput
    extends RadioInputComponent
    implements FormControlInterface, FieldInterface, LabelInterface, HelpTextInterface, AutoTranslationInterface
{
    use FormControlTrait,
        FieldTrait,
        LabelTrait,
        HelpTextTrait,
        AutoTranslationTrait;

    /**
     * RadioInput constructor.
     *
     * @param string $name
     * @param string $value
     */
    public function __construct(string $value, string $name = '')
    {
        parent::__construct();
        $this->name($name);
        $this->value($value);
        $this->setupFormControl();
    }

    /**
     * Apply a value to a field.
     *
     * @param $value
     */
    public function applyFieldValue($value)
    {
        if (is_bool($value)) {
            $value = (int)$value;
        }
        $this->checked((string)$value === $this->attributes->value);
    }

    /**
     * Does this field currently have a value set?
     *
     * @return bool
     */
    public function fieldHasValue()
    {
        return $this->attributes->isSet('checked');
    }

    /**
     * Returns the base translation-key for auto-translations for this object.
     *
     * @return string
     */
    public function getAutoTranslationKey(): string
    {
        return $this->attributes->name . '_' . $this->attributes->value;
    }
}
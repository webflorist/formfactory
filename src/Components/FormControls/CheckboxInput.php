<?php

namespace Nicat\FormFactory\Components\FormControls;

use Nicat\FormFactory\Components\FormControls\Traits\FieldTrait;
use Nicat\FormFactory\Components\FormControls\Contracts\FieldInterface;
use Nicat\FormFactory\Components\FormControls\Traits\FormControlTrait;
use Nicat\FormFactory\Components\FormControls\Contracts\FormControlInterface;
use Nicat\FormFactory\Components\FormControls\Traits\HelpTextTrait;
use Nicat\FormFactory\Components\FormControls\Traits\LabelTrait;
use Nicat\FormFactory\Components\FormControls\Contracts\AutoTranslationInterface;
use Nicat\FormFactory\Components\FormControls\Traits\AutoTranslationTrait;
use Nicat\HtmlFactory\Components\CheckboxInputComponent;

class CheckboxInput
    extends CheckboxInputComponent
    implements FormControlInterface, FieldInterface,   AutoTranslationInterface
{
    use FormControlTrait,
        FieldTrait,
        LabelTrait,
        HelpTextTrait,
        AutoTranslationTrait;

    /**
     * CheckboxInput constructor.
     *
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value = "1")
    {
        parent::__construct();
        $this->name($name);
        $this->value($value);
        $this->setupFormControl();
    }

    /**
     * Gets called after applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function afterDecoration()
    {
        parent::afterDecoration();
        $this->processFormControl();
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
}
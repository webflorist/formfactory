<?php

namespace Webflorist\FormFactory\Components\FormControls;

use Webflorist\FormFactory\Components\FormControls\Traits\ErrorsTrait;
use Webflorist\FormFactory\Components\FormControls\Traits\FieldTrait;
use Webflorist\FormFactory\Components\FormControls\Contracts\FieldInterface;
use Webflorist\FormFactory\Components\FormControls\Traits\FormControlTrait;
use Webflorist\FormFactory\Components\FormControls\Contracts\FormControlInterface;
use Webflorist\FormFactory\Components\FormControls\Traits\HelpTextTrait;
use Webflorist\FormFactory\Components\FormControls\Traits\LabelTrait;
use Webflorist\FormFactory\Components\FormControls\Contracts\AutoTranslationInterface;
use Webflorist\FormFactory\Components\FormControls\Traits\AutoTranslationTrait;
use Webflorist\FormFactory\Components\FormControls\Traits\RulesTrait;
use Webflorist\HtmlFactory\Elements\SelectElement;

class Select
    extends SelectElement
    implements FormControlInterface, FieldInterface,   AutoTranslationInterface
{
    use FormControlTrait,
        FieldTrait,
        ErrorsTrait,
        RulesTrait,
        LabelTrait,
        HelpTextTrait,
        AutoTranslationTrait;

    /**
     * Select constructor.
     *
     * @param string $name
     * @param array $options
     */
    public function __construct(string $name, array $options = [])
    {
        parent::__construct();
        $this->name($name);
        foreach ($options as $option) {
            $this->appendContent($option);
        }
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
        // $value may be a string (in case of a non-multiple-select) or an array (in case of a multiple-select).
        // We make sure, $value is an array in any case.
        if (!is_array($value)) {
            $value = [$value];
        }

        // Format each option.
        foreach ($this->content->getChildrenByClassName(Option::class) as $optionKey => $option) {

            /** @var Option $option */
            $option->selected(
                in_array($option->attributes->value, $value)
            );

        }
    }

    /**
     * Does this field currently have a value set?
     *
     * @return bool
     */
    public function fieldHasValue()
    {
        foreach ($this->content->getChildrenByClassName(Option::class) as $optionKey => $option) {

            /** @var Option $option */
            if ($option->attributes->isSet('selected')) {
                return true;
            }

        }

        return false;
    }
}
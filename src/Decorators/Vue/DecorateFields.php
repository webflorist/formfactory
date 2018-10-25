<?php

namespace Nicat\FormFactory\Decorators\Vue;

use Nicat\FormFactory\Utilities\ComponentLists;
use Nicat\FormFactory\Components\FormControls\FileInput;
use Nicat\FormFactory\Utilities\FormFactoryTools;
use Nicat\HtmlFactory\Attributes\Traits\AllowsVueModelDirective;
use Nicat\HtmlFactory\Decorators\Abstracts\Decorator;
use Nicat\HtmlFactory\Elements\Abstracts\Element;

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
        return null;
    }

    /**
     * Returns an array of class-names of elements, that should be decorated by this decorator.
     *
     * @return string[]
     */
    public static function getSupportedElements(): array
    {
        return ComponentLists::fields();
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        $fieldName = $this->element->attributes->name;
        $fieldBase = "fields['$fieldName']";
        if (!$this->element->attributes->isSet('v-model') && !$this->element->is(FileInput::class)) {
            $this->element->vModel($fieldBase . '.value');
        }
        if (!$this->element->attributes->isSet('v-bind')) {
            $this->element->vBind(null,
                '{ required: ' . $fieldBase . '.isRequired, disabled: ' . $fieldBase . '.isDisabled }'
            );
        }

    }

}
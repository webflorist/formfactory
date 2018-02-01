<?php

namespace Nicat\FormBuilder\Decorators\General;

use Nicat\FormBuilder\Components\FieldWrapper;
use Nicat\FormBuilder\RulesProcessor\RulesProcessor;
use Nicat\FormBuilder\ValueProcessor\ValueProcessor;
use Nicat\FormBuilder\Elements\CheckboxInputElement;
use Nicat\FormBuilder\Elements\ColorInputElement;
use Nicat\FormBuilder\Elements\DateInputElement;
use Nicat\FormBuilder\Elements\DatetimeInputElement;
use Nicat\FormBuilder\Elements\DatetimeLocalInputElement;
use Nicat\FormBuilder\Elements\EmailInputElement;
use Nicat\FormBuilder\Elements\FileInputElement;
use Nicat\FormBuilder\Elements\HiddenInputElement;
use Nicat\FormBuilder\Elements\NumberInputElement;
use Nicat\FormBuilder\Elements\OptionElement;
use Nicat\FormBuilder\Elements\RadioInputElement;
use Nicat\FormBuilder\Elements\SelectElement;
use Nicat\FormBuilder\Elements\TextareaElement;
use Nicat\FormBuilder\Elements\TextInputElement;
use Nicat\FormBuilder\Elements\Traits\CanHaveHelpText;
use Nicat\FormBuilder\Elements\Traits\CanHaveLabel;
use Nicat\FormBuilder\Elements\Traits\UsesAutoTranslation;
use Nicat\FormBuilder\FormBuilderTools;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;
use Nicat\HtmlBuilder\Elements\Abstracts\Element;
use Nicat\HtmlBuilder\Elements\Traits\AllowsPlaceholderAttribute;

/**
 * Apply various decorations to FormBuilder-options.
 *
 * Class DecorateFields
 * @package Nicat\FormBuilder\Decorators\General
 */
class DecorateOptions extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var OptionElement
     */
    protected $element;

    /**
     * Returns an array of frontend-framework-ids, this decorator is specific for.
     * Returning an empty array means all frameworks are supported.
     *
     * @return string[]
     */
    public static function getSupportedFrameworks(): array
    {
        return [];
    }

    /**
     * Returns an array of class-names of elements, that should be decorated by this decorator.
     *
     * @return string[]
     */
    public static function getSupportedElements(): array
    {
        return [
            OptionElement::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        // Automatically generate a meaningful id for elements without a manually set id.
        $this->autoGenerateID();

        // Automatically generate the content-text for options without a manually set content using auto-translation.
        $this->autoGenerateContentText();

    }

    /**
     * Automatically generates a meaningful id for fields without a manually set id.
     */
    protected function autoGenerateID()
    {
        // If the element already has an id, we leave it be.
        if ($this->element->attributes->isSet('id')) {
            return;
        }

        // We retrieve the SelectElement this OptionElement belongs to from the formbuilder-service
        $selectElement = $this->formBuilder->openSelect;

        // If this option's select-box has no 'name' attribute set, we abort,
        // because without a name we can not auto-create an id.
        if (!$selectElement->attributes->isSet('name')) {
            return;
        }

        // Auto-generated IDs always start with formID...
        $fieldId = $this->formBuilder->openForm->attributes->id;

        // ...followed by the field-name of the SelectElement....
        $fieldId .= '_' . $selectElement->attributes->name;

        // ...and the option's value.
        $fieldId .= '_' . $this->element->attributes->value;

        $this->element->id($fieldId);
    }

    /**
     * Automatically generates the option's content-text (label) using auto-translation
     */
    private function autoGenerateContentText()
    {
        if (!$this->element->hasChildren()) {
            $this->element->content(
                $this->element->performAutoTranslation($this->element->attributes->value)
            );
        }
    }

}
<?php

namespace Nicat\FormBuilder\Decorators\General;

use Nicat\FormBuilder\Components\FormControls\Option;
use Nicat\FormBuilder\FormBuilder;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;

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
     * @var Option
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
            Option::class
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

        // We retrieve the Select this Option belongs to from the formbuilder-service
        $select = app(FormBuilder::class)->openSelect;

        // If this option's select-box has no 'name' attribute set, we abort,
        // because without a name we can not auto-create an id.
        if (!$select->attributes->isSet('name')) {
            return;
        }

        // Auto-generated IDs always start with formID...
        $fieldId = app(FormBuilder::class)->openForm->attributes->id;

        // ...followed by the field-name of the Select....
        $fieldId .= '_' . $select->attributes->name;

        // ...and the option's value.
        $fieldId .= '_' . $this->element->attributes->value;

        $this->element->id($fieldId);
    }

    /**
     * Automatically generates the option's content-text (label) using auto-translation
     */
    private function autoGenerateContentText()
    {
        if (!$this->element->content->hasContent()) {
            $this->element->content(
                $this->element->performAutoTranslation($this->element->attributes->value)
            );
        }
    }

}
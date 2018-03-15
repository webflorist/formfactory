<?php

namespace Nicat\FormBuilder\Decorators\General;

use Nicat\FormBuilder\Components\FormControls\Button;
use Nicat\FormBuilder\Components\FormControls\ResetButton;
use Nicat\FormBuilder\Components\FormControls\SubmitButton;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;

/**
 * Apply various decorations to FormBuilder-fields.
 *
 * Class AutoGenerateButtonTexts
 * @package Nicat\FormBuilder\Decorators\General
 */
class DecorateButtons extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var Button
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
            Button::class,
            SubmitButton::class,
            ResetButton::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        // Automatically generate a meaningful id for fields without a manually set id.
        $this->autoGenerateID();

        // Automatically generate the button-text for button-elements without a manually set content using auto-translation.
        $this->autoGenerateButtonText();

    }

    /**
     * Automatically generates a meaningful id for buttons without a manually set id.
     */
    protected function autoGenerateID()
    {
        // If the element already has an id, we leave it be.
        if ($this->element->attributes->isSet('id')) {
            return;
        }

        // Auto-generated IDs always start with formID followed by an underscore and an appropriate suffix.
        $fieldId = $this->formBuilder->openForm->attributes->id . '_' . $this->getIdSuffix();

        // Set the id.
        $this->element->id($fieldId);
    }

    /**
     * Returns an appropriate ID-suffix for autogenerated button-IDs.
     *
     * @return string
     */
    protected function getIdSuffix(): string
    {

        // If the "name"-attribute is set, we use that as suffix.
        if ($this->element->attributes->isSet('name')) {
            return $this->element->attributes->name;
        }

        // If the button has a "context", we use that as suffix.
        if ($this->element->hasContext()) {
            return $this->element->getContext();
        }

        // As default-suffix, we use the string 'button'.
        return 'button';
    }

    /**
     * Automatically generates the button-text for button-elements without a manually set content using auto-translation.
     */
    private function autoGenerateButtonText()
    {
        if (!$this->element->content->hasContent()) {
            $this->element->content(
                $this->element->performAutoTranslation(ucwords($this->element->attributes->name))
            );
        }
    }

}
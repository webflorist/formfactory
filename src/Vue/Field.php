<?php

namespace Webflorist\FormFactory\Vue;

use Illuminate\Database\Eloquent\Collection;
use Spatie\MediaLibrary\Models\Media;
use Webflorist\FormFactory\Components\FormControls\CheckboxInput;
use Webflorist\FormFactory\Components\FormControls\FileInput;
use Webflorist\FormFactory\Components\FormControls\Option;
use Webflorist\FormFactory\Components\FormControls\RadioInput;
use Webflorist\FormFactory\Components\FormControls\Select;
use Webflorist\FormFactory\Utilities\FormFactoryTools;
use Webflorist\HtmlFactory\Elements\Abstracts\Element;
use Webflorist\HtmlFactory\Elements\TextareaElement;

/**
 * Object representing a "field" inside the "data.fieldData" object of a VueInstance.
 *
 * Class VueInstanceGenerator
 * @package Webflorist\FormFactory
 */
class Field
{

    /**
     * The value of this field.
     * This will be used for model-binding.
     *
     * @var bool|string|array
     */
    public $value = '';

    /**
     * Is this field required?
     *
     * Both the field's 'required' attribute as well as
     * the display of its RequiredFieldIndicator will react to this setting.
     *
     * @var bool
     */
    public $isRequired = false;

    /**
     * Is this field disabled?
     *
     * The field's 'disabled' attribute will react to this setting.
     *
     * @var bool
     */
    public $isDisabled = false;

    /**
     * The fieldData-object this field is a part of.
     *
     * @var \stdClass
     */
    private $fieldData;

    /**
     * Field constructor.
     *
     * @param Element $field
     * @param \stdClass $fieldData
     */
    public function __construct(Element $field, \stdClass $fieldData)
    {
        $this->fieldData = $fieldData;
        $this->value = $this->evaluateFieldValue($field);
        $this->isRequired = ($field->attributes->required === true) ? true : false;
        $this->isDisabled = ($field->attributes->disabled === true) ? true : false;
    }

    /**
     * Evaluates the field's current value.
     *
     * @param Element $field
     * @return bool|mixed|string
     */
    private function evaluateFieldValue(Element $field)
    {
        if ($field->is(CheckboxInput::class)) {
            return ($field->attributes->checked === true);
        }

        if ($field->is(RadioInput::class)) {
            return $this->evaluateRadioInputValue($field);
        }

        if ($field->is(TextareaElement::class)) {
            /** @var TextareaElement $field */
            return $field->generateContent();
        }

        if ($field->is(Select::class)) {
            return $this->evaluateSelectValue($field);
        }

        if ($field->is(FileInput::class)) {
            return $this->evaluateFileValue($field);
        }

        if (FormFactoryTools::isArrayField($field->attributes->name) && is_null($field->attributes->value)) {
            return [];
        }

        return $field->attributes->value;

    }

    /**
     * Evaluate current value of a RadioInput.
     *
     * @param RadioInput $radio
     * @return string
     */
    private function evaluateRadioInputValue(RadioInput $radio): string
    {
        $fieldName = $radio->attributes->name;

        // If a valid value for this field-name was already saved from a different radio with the same name, we keep it.
        if (property_exists($this->fieldData, $fieldName) && (strlen($this->fieldData->{$fieldName}->value) > 0)) {
            return $this->fieldData->{$fieldName}->value;
        }

        // Otherwise, if $radio is checked, we return it's "value"-attribute.
        return ($radio->attributes->checked === true) ? $radio->attributes->value : '';
    }

    /**
     * Evaluate current value(s) of a Select.
     *
     * @param Select $field
     * @return array|string
     */
    private function evaluateSelectValue(Select $field)
    {
        $isMultiple = $field->attributes->multiple === true;

        $return = '';
        if ($isMultiple) {
            $return = [];
        }

        foreach ($field->content->getChildrenByClassName(Option::class) as $optionKey => $option) {

            $value = $option->attributes->value;

            /** @var Option $option */
            if ($option->attributes->isSet('selected') && $option->attributes->selected) {
                if (!$isMultiple) {
                    $return = $value;
                    break;
                }
                $return[] = $value;
            }

        }

        return $return;
    }

    /**
     * Evaluate current value(s) of a Select.
     *
     * @param Select $field
     * @return array|string
     */
    private function evaluateFileValue(FileInput $field)
    {
        $value = $field->attributes->value;
        if (FormFactoryTools::isArrayField($field->attributes->name)) {
            if ($value instanceof Collection) {
                $processedValue = [];
                foreach ($value as $key => $file) {
                    $processedValue[$key] = $this->processFile($file);
                }
                $value = $processedValue;
            }
            else {
                $value = [];
            }
        }
        else {
            $value = $this->processFile($value);
        }
        return $value;
    }

    private function processFile($file)
    {
        if (is_object($file) && is_a($file, 'Spatie\MediaLibrary\Models\Media')) {
            /** @var Media $file */
            return (object)[
                'customName' => $file->name,
                'upload' => (object) [
                    'media_id' => $file->id
                ],
                'name' => $file->file_name,
                'type' => $file->type,
                'size' => $file->size,
                'url' => $file->getFullUrl()
            ];
        }
        return $file;
    }
}
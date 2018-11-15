<?php

namespace Webflorist\FormFactory\Components\FormControls;

use Webflorist\FormFactory\Components\Helpers\ErrorContainer;
use Webflorist\FormFactory\Components\Helpers\HelpTextContainer;
use Webflorist\FormFactory\Components\Helpers\FieldLabel;
use Webflorist\FormFactory\Components\FormControls\Contracts\FieldInterface;
use Webflorist\FormFactory\Components\FormControls\Traits\FieldTrait;
use Webflorist\FormFactory\Components\FormControls\Traits\FormControlTrait;
use Webflorist\FormFactory\Components\FormControls\Traits\HelpTextTrait;
use Webflorist\FormFactory\Components\FormControls\Traits\LabelTrait;
use Webflorist\HtmlFactory\Elements\DivElement;

class InputGroup
    extends DivElement
{

    use LabelTrait;

    /**
     * The main field of this InputGroup.
     * It's FieldLabel will be used as this InputGroup's FieldLabel.
     *
     * @var FieldInterface
     */
    public $mainField = null;

    /**
     * The FieldLabel to be displayed with this InputGroup.
     *
     * @var FieldLabel
     */
    public $label = null;

    /**
     * Array of all FieldHelpTexts, that are contained in this InputGroup.
     *
     * @var HelpTextContainer[]
     */
    public $containedHelpTexts = [];

    /**
     * Array of all ErrorContainer, that are contained in this InputGroup.
     *
     * @var ErrorContainer[]
     */
    public $containedErrors = [];

    /**
     * InputGroup constructor.
     *
     * @param string $name
     * @param array $content
     */
    public function __construct(array $content)
    {
        parent::__construct();

        // Apply view.
        $this->view('formfactory::form-controls.input-group');

        // Set content.
        $this->content($content);

        // Get first Field of $content and save it in $this->mainField.
        $this->mainField = $this->content->getChildrenByClassName(FieldInterface::class)[0];

    }

    /**
     * Gets called before applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function afterDecoration()
    {

        // Steal label and fieldWrapper from main field.
        $this->label = clone $this->mainField->label;
        $this->wrap($this->mainField->wrapper);

        // Clone HelpTexts and Errors from and fields in this InputGroup.
        // Then set the field's ones to hide.
        // Also deactivate FieldWrapper.
        foreach ($this->content->getChildrenByClassName(FieldInterface::class) as $childKey => $child) {
            /** @var FieldTrait|FormControlTrait|HelpTextTrait|LabelTrait $child */

            $this->containedErrors[] = clone $child->errors;
            $child->errors->hideErrors();

            if ($child->canHaveHelpText()) {
                $this->containedHelpTexts[] = clone $child->helpText;
                $child->helpText->hideHelpText();
            }

            if ($child->canHaveLabel()) {
                $child->label->displayLabel = false;
            }

            $child->wrap(false);
        }

        // Make sure, all helpers are generated.
        $this->label->generate();
        foreach($this->containedHelpTexts as $helpText) {
            $helpText->generate();
        }
        foreach($this->containedErrors as $errors) {
            $errors->generate();
        }
    }

    /**
     * Returns the base translation-key for auto-translations for this object.
     *
     * @return string
     */
    public function getAutoTranslationKey(): string
    {
        return $this->mainField->getAutoTranslationKey();
    }

    /**
     * @inheritDoc
     */
    public function getFieldName(): string
    {
        return $this->mainField->getFieldName();
    }

    /**
     * Defer setting of aria-invalid attribute to the main field.
     *
     * @param $invalid
     * @return $this
     */
    public function ariaInvalid($invalid)
    {
        $this->mainField->ariaInvalid($invalid);
        return $this;
    }

    /**
     * Defer setting of aria-describedby attribute to the main field.
     *
     * @param $id
     * @return $this
     */
    public function addAriaDescribedby($id)
    {
        $this->mainField->addAriaDescribedby($id);
        return $this;
    }

    /**
     * Defer setting of rules to input-inputs.
     *
     * @param $rules
     * @return $this
     */
    public function rules($rules)
    {
        $this->mainField->rules($rules);
        return $this;
    }


}
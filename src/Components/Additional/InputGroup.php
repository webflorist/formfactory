<?php

namespace Nicat\FormFactory\Components\Additional;

use Nicat\FormFactory\Components\DynamicLists\DynamicList;
use Nicat\FormFactory\Components\DynamicLists\DynamicListTemplateInterface;
use Nicat\FormFactory\Components\FormControls\Button;
use Nicat\FormFactory\Components\Traits\CanHaveLabel;
use Nicat\HtmlFactory\Components\CheckboxInputComponent;
use Nicat\HtmlFactory\Components\RadioInputComponent;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\ButtonElement;
use Nicat\HtmlFactory\Elements\DivElement;

class InputGroup extends DivElement implements DynamicListTemplateInterface
{

    /**
     * The FieldWrapper for this InputGroup.
     *
     * @var FieldWrapper
     */
    private $fieldWrapper;

    /**
     * Gets called during construction.
     * Overwrite to perform setup-functionality.
     */
    protected function setUp()
    {
        $this->fieldWrapper = new FieldWrapper();
        $this->wrap($this->fieldWrapper);
        $this->addClass('input-group');
    }

    /**
     * Gets called after applying decorators to the child-elements.
     * Overwrite to perform manipulations.
     */
    protected function afterChildrenDecoration()
    {
        $this->formatFieldChildren();
    }

    /**
     * Returns all children, that are fields (=can have the "name" attribute).
     *
     * @return Element[]
     */
    private function getFieldChildren()
    {
        $fieldChildren = [];
        foreach ($this->content->getChildrenByClassName(Element::class,true) as $child) {
            if ($child->attributes->isAllowed('name') && !$child->is(ButtonElement::class)) {
                $fieldChildren[] = $child;
            }
        }
        return $fieldChildren;
    }

    /**
     * Adds a field-name to the list of fields, the FieldWrapper of this InputGroup should display errors for.
     *
     * @param string $fieldName
     * @return InputGroup
     */
    public function addErrorField(string $fieldName)
    {
        $this->fieldWrapper->errorContainer->addErrorField($fieldName);
        return $this;
    }

    /**
     * Formats all children of this input-group, that are fields.
     */
    private function formatFieldChildren()
    {

        foreach ($this->getFieldChildren() as $child) {

            // Disable the standard-wrapping of the child.
            $child->wrap(false);

            /** @var CanHaveLabel $child */
            if ($child->labelMode !== 'none') {

                // We tell $this->fieldWrapper->label to display
                // the label for the first child.
                if (is_null($this->fieldWrapper->label->field)) {
                    $this->fieldWrapper->label->field = $child;
                }

                // Only checkbox- or radio-children are allowed to have labels themselves.
                if ($child->is(RadioInputComponent::class) || $child->is(CheckboxInputComponent::class)) {
                    $child->wrap(new FieldLabel($child));
                }

            }

            // Tell the ErrorContainer to display errors for $child.
            $this->fieldWrapper->errorContainer->addErrorField($child);

            // Tell the HelpTextContainer to display help-texts for $child.
            $this->fieldWrapper->helpTextContainer->addHelpTextField($child);
        }
    }

    /**
     * Manipulate this object to add the $removeItemButton needed for dynamicLists
     * and perform other needed modifications.
     *
     * @param DynamicList $dynamicList
     * @param Button $removeItemButton
     * @return void
     */
    function performDynamicListModifications(DynamicList $dynamicList, Button $removeItemButton)
    {
        $this->formatFieldChildren();
        $this->wrap(false);
        $this->insertBefore($this->fieldWrapper->errorContainer);
        $this->addClass('m-b-1');
        $this->prependContent(new InputGroupButton($removeItemButton));

    }
}
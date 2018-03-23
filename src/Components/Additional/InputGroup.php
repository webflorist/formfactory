<?php

namespace Nicat\FormFactory\Components\Additional;

use Nicat\FormFactory\Components\DynamicLists\DynamicList;
use Nicat\FormFactory\Components\DynamicLists\DynamicListTemplateInterface;
use Nicat\FormFactory\Components\FormControls\Button;
use Nicat\FormFactory\Components\HelpText\HelpTextInterface;
use Nicat\FormFactory\Components\Traits\CanHaveLabel;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\DivElement;
use Nicat\HtmlFactory\Elements\LabelElement;

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
        foreach ($this->content->get() as $child) {
            if ($child->attributes->isAllowed('name')) {
                $fieldChildren[] = $child;
            }
        }
        return $fieldChildren;
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
                $child->labelMode('sr-only');

                // We tell $this->fieldWrapper->label to display
                // the label for the first child.
                if (is_null($this->fieldWrapper->label->field)) {
                    $this->fieldWrapper->label->field = $child;
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
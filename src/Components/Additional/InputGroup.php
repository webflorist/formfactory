<?php

namespace Nicat\FormFactory\Components\Additional;

use Nicat\FormFactory\Components\DynamicLists\DynamicList;
use Nicat\FormFactory\Components\DynamicLists\DynamicListTemplateInterface;
use Nicat\FormFactory\Components\FormControls\Button;
use Nicat\FormFactory\Components\Traits\CanHaveLabel;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\DivElement;
use Nicat\HtmlFactory\Elements\LabelElement;

class InputGroup extends DivElement implements DynamicListTemplateInterface
{

    /**
     * Any errors for fields contained in this input-group will be displayed here.
     *
     * @var ErrorWrapper
     */
    private $errorWrapper;

    /**
     * Gets called during construction.
     * Overwrite to perform setup-functionality.
     */
    protected function setUp()
    {
        $this->errorWrapper = new ErrorWrapper();
        $this->insertBefore($this->errorWrapper);
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
     * Manipulate the generated HTML.
     *
     * @param string $output
     */
    protected function manipulateOutput(string &$output)
    {
        // We extract the label of the first field-element and render it' label before the InputGroup.
        if (!isset($this->isDynamicListTemplate) || ($this->isDynamicListTemplate === false)) {
            foreach ($this->content->get() as $childKey => $child) {
                if (property_exists($child, 'label') and $child->labelMode !== 'none') {
                    /** @var CanHaveLabel $child */
                    $output = (new LabelElement())->content($child->label)->for($child->attributes->id)->generate() . $output;
                    break;
                }
            }
        }
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
            }

            // Tell $this->errorWrapper to display errors for $child.
            $this->errorWrapper->addErrorField($child);
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
        $this->addClass('m-b-1');
        $this->prependContent(new InputGroupButton($removeItemButton));
    }

}
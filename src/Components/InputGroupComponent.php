<?php

namespace Nicat\FormBuilder\Components;

use Nicat\FormBuilder\Components\Contracts\DynamicListTemplateInterface;
use Nicat\FormBuilder\Elements\ButtonElement;
use Nicat\FormBuilder\Elements\Traits\CanHaveLabel;
use Nicat\HtmlBuilder\Elements\Abstracts\Element;
use Nicat\HtmlBuilder\Elements\DivElement;
use Nicat\HtmlBuilder\Elements\LabelElement;

class InputGroupComponent extends DivElement implements DynamicListTemplateInterface
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
     * Gets called before applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function beforeDecoration()
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
            foreach ($this->getChildren() as $childKey => $child) {
                if (property_exists($child, 'label') and $child->labelMode != 'none') {
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
        foreach ($this->getChildren() as $child) {
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
            $child->labelMode('sr-only');

            // Tell $this->errorWrapper to display errors for $child.
            $this->errorWrapper->addErrorField($child);
        }
    }

    /**
     * Manipulate this object to add the $removeItemButton needed for dynamicLists
     * and perform other needed modifications.
     *
     * @param DynamicList $dynamicList
     * @param ButtonElement $removeItemButton
     * @return void
     */
    function performDynamicListModifications(DynamicList $dynamicList, ButtonElement $removeItemButton)
    {
        $this->addClass('m-b-1');
        $this->prependChild(new InputGroupButtonComponent($removeItemButton));
    }
}
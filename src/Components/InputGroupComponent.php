<?php

namespace Nicat\FormBuilder\Components;

use Nicat\FormBuilder\Components\Contracts\DynamicListTemplateInterface;
use Nicat\FormBuilder\Elements\ButtonElement;
use Nicat\HtmlBuilder\Elements\Abstracts\Element;
use Nicat\HtmlBuilder\Elements\DivElement;

class InputGroupComponent extends DivElement implements DynamicListTemplateInterface
{
    /**
     * Any errors for fields contained in this input-group will be displayed here.
     *
     * @var ErrorWrapper
     */
    private $errorWrapper;

    /**
     * InputGroupComponent constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->errorWrapper = new ErrorWrapper();
        $this->addClass('input-group');
    }

    /**
     * Gets called before applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function afterDecoration()
    {
        $this->formatFieldChildren();

    }

    /**
     * Take the button intended to remove this dynamic list item and put it where you see fit.
     *
     * @param ButtonElement $button
     * @return void
     */
    function implementRemoveItemButton(ButtonElement $button)
    {
        $inputGroupButton = new InputGroupButtonComponent($button);
        $this->prependChild($inputGroupButton);
    }

    /**
     * Manipulate this object to add the deleteRow-button needed for dynamicLists
     * and perform other needed modifications.
     *
     * @param DynamicList $dynamicList
     * @return void
     */
    function performDynamicListModifications(DynamicList $dynamicList)
    {
        $this->addClass('m-b-1');
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

            // Tell $this->errorWrapper to display errors for $child.
            $this->errorWrapper->addErrorField($child);
        }
    }
}
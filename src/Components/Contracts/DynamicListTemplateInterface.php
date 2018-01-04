<?php

namespace Nicat\FormBuilder\Components\Contracts;

use Nicat\FormBuilder\Components\DynamicList;
use Nicat\FormBuilder\Elements\ButtonElement;

interface DynamicListTemplateInterface
{
    /**
     * Perform general modifications needed for dynamic list items.
     *
     * @param DynamicList $dynamicList
     * @return void
     */
    function performDynamicListModifications(DynamicList $dynamicList);

    /**
     * Take the button intended to remove this dynamic list item and put it where you see fit.
     *
     * @param ButtonElement $button
     * @return void
     */
    function implementRemoveItemButton(ButtonElement $button);
}
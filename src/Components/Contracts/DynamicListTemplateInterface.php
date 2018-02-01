<?php

namespace Nicat\FormBuilder\Components\Contracts;

use Nicat\FormBuilder\Components\DynamicList;
use Nicat\FormBuilder\Elements\ButtonElement;

interface DynamicListTemplateInterface
{

    /**
     * Manipulate this object to add the $removeItemButton needed for dynamicLists
     * and perform other needed modifications.
     *
     * @param DynamicList $dynamicList
     * @param ButtonElement $removeItemButton
     * @return void
     */
    function performDynamicListModifications(DynamicList $dynamicList, ButtonElement $removeItemButton);
}
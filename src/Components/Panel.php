<?php

namespace Nicat\FormBuilder\Components;

use Nicat\FormBuilder\Components\Contracts\DynamicListTemplateInterface;
use Nicat\FormBuilder\Elements\ButtonElement;
use Nicat\HtmlBuilder\Components\PanelComponent;
use Nicat\HtmlBuilder\Elements\SpanElement;

class Panel extends PanelComponent implements DynamicListTemplateInterface
{

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
        $removeItemButton->addClass('pull-right')->addClass('btn-sm');
        $this->contentWrapper->insertBefore($removeItemButton);
        $this->contentWrapper->insertBefore((new SpanElement())->addClass('clearfix'));
        $this->addClass('m-b-1');
    }

}
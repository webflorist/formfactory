<?php

namespace Nicat\FormFactory\Components\Additional;

use Nicat\FormFactory\Components\DynamicLists\DynamicList;
use Nicat\FormFactory\Components\DynamicLists\DynamicListTemplateInterface;
use Nicat\FormFactory\Components\FormControls\Button;
use Nicat\HtmlFactory\Components\PanelComponent;
use Nicat\HtmlFactory\Elements\SpanElement;

class Panel extends PanelComponent implements DynamicListTemplateInterface
{

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
        $removeItemButton->addClass('pull-right')->addClass('btn-sm');
        $this->contentWrapper->insertBefore($removeItemButton);
        $this->contentWrapper->insertBefore((new SpanElement())->addClass('clearfix'));
        $this->addClass('m-b-1');
    }

}
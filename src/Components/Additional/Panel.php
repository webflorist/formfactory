<?php

namespace Webflorist\FormFactory\Components\Additional;

use Webflorist\FormFactory\Components\DynamicLists\DynamicList;
use Webflorist\FormFactory\Components\DynamicLists\DynamicListTemplateInterface;
use Webflorist\FormFactory\Components\FormControls\Button;
use Webflorist\HtmlFactory\Components\PanelComponent;
use Webflorist\HtmlFactory\Elements\SpanElement;

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
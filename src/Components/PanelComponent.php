<?php

namespace Nicat\FormBuilder\Components;

use Nicat\FormBuilder\Components\Contracts\DynamicListTemplateInterface;
use Nicat\FormBuilder\Elements\ButtonElement;
use Nicat\HtmlBuilder\Elements\SpanElement;

class PanelComponent extends \Nicat\HtmlBuilder\Components\PanelComponent implements DynamicListTemplateInterface
{

    /**
     * Manipulate this object to add the deleteRow-button needed for dynamicLists
     * and perform other needed modifications.
     *
     * @param DynamicList $dynamicList
     * @return void
     */
    function performDynamicListModifications(DynamicList $dynamicList)
    {
        $this->contentWrapper->prependContent((new SpanElement())->addClass('clearfix'));
        $this->addClass('m-b-1');
    }

    /**
     * Take the button intended to remove this dynamic list item and put it where you see fit.
     *
     * @param ButtonElement $button
     * @return void
     */
    function implementRemoveItemButton(ButtonElement $button)
    {
        $button->addClass('pull-right')->addClass('btn-sm');
        $this->contentWrapper->prependContent($button);
    }
}
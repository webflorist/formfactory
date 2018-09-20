<?php

namespace Nicat\FormFactory\Decorators\Bootstrap\v3;

use Nicat\FormFactory\Components\DynamicLists\DynamicList;
use Nicat\HtmlFactory\Decorators\Abstracts\Decorator;

class StyleDynamicLists extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var DynamicList
     */
    protected $element;

    /**
     * Returns the group-ID of this decorator.
     *
     * Returning null means this decorator will always be applied.
     *
     * @return string|null
     */
    public static function getGroupId()
    {
        return 'bootstrap:v3';
    }

    /**
     * Returns an array of class-names of elements, that should be decorated by this decorator.
     *
     * @return string[]
     */
    public static function getSupportedElements(): array
    {
        return [
            DynamicList::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {

        $this->element->addItemButton
            ->addClass('btn-sm push-top')
            ->appendContent(' <i class="btr bt-plus btn-no-anim"></i>')
        ;

        $this->element->removeItemButton->content->clear();
        $this->element->removeItemButton->content('<i class="btr bt-trash" style="margin:0"></i>');

    }
}
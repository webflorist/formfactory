<?php

namespace Nicat\FormBuilder\Decorators\Bootstrap\v3;

use Nicat\FormBuilder\Components\DynamicList;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;

class StyleDynamicLists extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var DynamicList
     */
    protected $element;

    /**
     * Returns an array of frontend-framework-ids, this decorator is specific for.
     *
     * @return string[]
     */
    public static function getSupportedFrameworks(): array
    {
        return [
            'bootstrap:3'
        ];
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

        $this->element->removeItemButton
            ->clearContent()
            ->content('<i class="btr bt-trash" style="margin:0"></i>')
        ;

    }
}
<?php

namespace Webflorist\FormFactory\Decorators\Bootstrap\v3;

use Webflorist\FormFactory\Components\Helpers\ErrorContainer;
use Webflorist\FormFactory\Components\Helpers\HelpTextContainer;
use Webflorist\HtmlFactory\Decorators\Abstracts\Decorator;
use Webflorist\HtmlFactory\Elements\Abstracts\Element;

class StyleErrorContainer extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var ErrorContainer
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
            ErrorContainer::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        $this->element->addRole('alert');
        $this->element->addClass('alert');
        $this->element->addClass('alert-danger');
    }
}

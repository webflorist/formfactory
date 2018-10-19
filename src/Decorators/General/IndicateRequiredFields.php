<?php

namespace Nicat\FormFactory\Decorators\General;

use Nicat\FormFactory\Components\Additional\RadioGroup;
use Nicat\FormFactory\Components\Additional\RequiredFieldIndicator;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\FormFactory\Components\Traits\CanHaveLabel;
use Nicat\FormFactory\Components\Traits\CanHaveRules;
use Nicat\FormFactory\Utilities\ComponentLists;
use Nicat\FormFactory\Utilities\Config\FormFactoryConfig;
use Nicat\HtmlFactory\Decorators\Abstracts\Decorator;
use Nicat\HtmlFactory\Elements\Abstracts\Element;

/**
 * Adds an indication to the label of required form fields.
 *
 * Class DecorateFields
 * @package Nicat\FormFactory\Decorators\General
 */
class IndicateRequiredFields extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var Element|CanHaveLabel
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
        return null;
    }

    /**
     * Returns an array of class-names of elements, that should be decorated by this decorator.
     *
     * @return string[]
     */
    public static function getSupportedElements(): array
    {
        return ComponentLists::labelableFields();
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {



    }


}
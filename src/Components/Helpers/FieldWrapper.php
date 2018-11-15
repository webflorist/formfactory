<?php

namespace Webflorist\FormFactory\Components\Helpers;

use Webflorist\FormFactory\Components\FormControls\Contracts\FieldInterface;
use Webflorist\FormFactory\Components\FormControls\Contracts\FormControlInterface;
use Webflorist\HtmlFactory\Elements\Abstracts\Element;
use Webflorist\HtmlFactory\Elements\DivElement;

class FieldWrapper extends DivElement
{

    /**
     * The field this FieldWrapper belongs to.
     *
     * @var Element|FormControlInterface
     */
    public $field;

    /**
     * Should this FieldWrapper be rendered?
     *
     * @var bool
     */
    public $render = true;

    /**
     * FieldWrapper constructor.
     *
     * @param Element|FieldInterface $field
     */
    public function __construct(FieldInterface $field)
    {
        parent::__construct();
        $this->field = $field;
    }

}
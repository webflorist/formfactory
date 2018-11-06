<?php

namespace Nicat\FormFactory\Components\Additional;

use Nicat\FormFactory\Components\Contracts\FieldInterface;
use Nicat\FormFactory\Components\Contracts\FormControlInterface;
use Nicat\FormFactory\Components\Contracts\LabelInterface;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\DivElement;

class FieldWrapper extends DivElement
{

    /**
     * The field this FieldWrapper belongs to.
     *
     * @var Element|LabelInterface|FormControlInterface|FieldInterface
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
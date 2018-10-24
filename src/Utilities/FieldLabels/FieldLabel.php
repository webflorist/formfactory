<?php

namespace Nicat\FormFactory\Utilities\FieldLabels;

use Nicat\HtmlFactory\Elements\Abstracts\Element;

class FieldLabel
{

    /**
     * The field this FieldLabel belongs to.
     *
     * @var Element
     */
    protected $field;

    /**
     * The label-text.
     *
     * @var string
     */
    protected $text;

    /**
     * FieldLabel constructor.
     *
     * @param Element|null $field
     */
    public function __construct($field = null)
    {
        $this->field = $field;
    }

    /**
     * Sets the label-text.
     *
     * @param $text
     * @return FieldLabel
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Returns the label-text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Returns label-text automatically on __toString.
     *
     * @return string
     * @throws \Throwable
     */
    public function __toString()
    {
        return $this->getText();
    }

}
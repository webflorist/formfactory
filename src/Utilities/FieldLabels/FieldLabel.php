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
     * Should the label be displayed?
     *
     * @var bool
     */
    public $displayLabel = true;

    /**
     * Should the label include an indicator for required fields?
     *
     * @var bool
     */
    public $displayRequiredFieldIndicator = true;

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
     * Is a label-text present?
     *
     * @return string
     */
    public function hasLabel()
    {
        return strlen($this->text) > 0;
    }

    /**
     * Do not display label.
     */
    public function hideLabel()
    {
        $this->displayLabel = false;
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
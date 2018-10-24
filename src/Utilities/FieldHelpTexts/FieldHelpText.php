<?php

namespace Nicat\FormFactory\Utilities\FieldHelpTexts;

use Nicat\HtmlFactory\Elements\Abstracts\Element;

class FieldHelpText
{

    /**
     * The field this FieldHelpText belongs to.
     *
     * @var Element
     */
    protected $field;

    /**
     * The help-text.
     *
     * @var string
     */
    protected $text;

    /**
     * FieldHelpText constructor.
     *
     * @param Element|null $field
     */
    public function __construct($field = null)
    {
        $this->field = $field;
    }

    /**
     * Sets the help-text.
     *
     * @param $text
     * @return FieldHelpText
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Returns the help-text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Returns help-text automatically on __toString.
     *
     * @return string
     * @throws \Throwable
     */
    public function __toString()
    {
        return $this->getText();
    }

}
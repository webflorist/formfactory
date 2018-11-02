<?php

namespace Nicat\FormFactory\Utilities\FieldHelpTexts;

use Nicat\FormFactory\Components\Contracts\FieldInterface;
use Nicat\FormFactory\Components\Contracts\FormControlInterface;
use Nicat\FormFactory\Components\Contracts\HelpTextInterface;

class FieldHelpText
{

    /**
     * The field this FieldHelpText belongs to.
     *
     * @var HelpTextInterface|FieldInterface|FormControlInterface
     */
    protected $field;

    /**
     * The help-text.
     *
     * @var null|string
     */
    protected $text;

    /**
     * Should the help-text be displayed?
     *
     * @var null|string
     */
    public $displayHelpText = true;

    /**
     * FieldHelpText constructor.
     *
     * @param HelpTextInterface $field
     */
    public function __construct(HelpTextInterface $field = null)
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
     * Is a help-text present?
     *
     * @return string
     */
    public function hasHelpText()
    {
        return strlen($this->text) > 0;
    }

    /**
     * Do not display help-text.
     */
    public function hideHelpText()
    {
        $this->displayHelpText = false;
    }

    /**
     * Returns the proposed ID for the help-text-container.
     * This will also be used for the aria-describedby attribute.
     */
    public function getContainerId()
    {
        $containerId = $this->field->getFieldName() . '_helpText';
        if ($this->field->hasFormInstance()) {
            $containerId = $this->field->getFormInstance()->getId() . '_' . $containerId;
        }
        return $containerId;
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
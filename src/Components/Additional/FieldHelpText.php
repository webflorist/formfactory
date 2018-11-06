<?php

namespace Nicat\FormFactory\Components\Additional;

use Nicat\FormFactory\Components\Contracts\FieldInterface;
use Nicat\FormFactory\Components\Contracts\FormControlInterface;
use Nicat\FormFactory\Components\Contracts\HelpTextInterface;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\FormFactory\Utilities\FormFactoryTools;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\SmallElement;

class FieldHelpText extends SmallElement
{

    /**
     * The field this FieldHelpText belongs to.
     *
     * @var HelpTextInterface|FieldInterface|FormControlInterface|Element
     */
    public $field;

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
    public function __construct(HelpTextInterface $field)
    {
        parent::__construct();

        $this->field = $field;

        $this->id(function () {
            return $this->field->attributes->id . '_helpText';
        });
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
     * Gets called before applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function beforeDecoration()
    {

        // Perform auto-translation, if no help-text was manually set.
        if (!$this->hasHelpText()) {
            $helpText = $this->field->performAutoTranslation(null, 'HelpText');
            if ($helpText !== null) {
                $this->setText($helpText);
            }
        }

        if ($this->displayHelpText && $this->hasHelpText()) {
            $this->field->addAriaDescribedby($this->attributes->id);
            $this->content($this->getText());
        }
    }

    /**
     * Don't render output, if help-text should not be displayed.
     *
     * @param string $output
     */
    protected function manipulateOutput(string &$output)
    {
        if (!$this->displayHelpText || !$this->hasHelpText()) {
            $output = '';
        }
    }

}
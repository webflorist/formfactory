<?php

namespace Nicat\FormFactory\Components\Helpers;

use Nicat\FormFactory\Components\FormControls\Contracts\FieldInterface;
use Nicat\FormFactory\Components\FormControls\Contracts\FormControlInterface;
use Nicat\FormFactory\Components\FormControls\TextInput;
use Nicat\FormFactory\Components\FormControls\Traits\HelpTextTrait;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\SmallElement;

class HelpTextContainer extends SmallElement
{

    /**
     * The field this HelpTextContainer belongs to.
     *
     * @var HelpTextTrait|FieldInterface|FormControlInterface|Element
     */
    public $field;

    /**
     * The help-text.
     *
     * @var null|string
     */
    protected $text;

    /**
     * Should aria-descriptionby attributes be applied to the field?
     *
     * @var bool
     */
    public $applyAriaAttribute = true;

    /**
     * Should the help-text be displayed?
     *
     * @var null|string
     */
    public $displayHelpText = true;

    /**
     * HelpTextContainer constructor.
     *
     * @param FieldInterface|string $field
     */
    public function __construct($field)
    {
        parent::__construct();

        // If we just get a field-name, we create a temporary text-input from it,
        // since a FieldInterface is required for further processing.
        if (is_string($field)) {
            $field = new TextInput($field);
        }

        $this->field = $field;

        $this->id(function () {
            return $this->field->attributes->id . '_helpText';
        });
    }

    /**
     * Sets the help-text.
     *
     * @param $text
     * @return HelpTextContainer
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
     *
     * By default also turns off application of the aria-attribute.
     *
     * @param bool $applyAriaAttribute
     */
    public function hideHelpText($applyAriaAttribute = false)
    {
        $this->displayHelpText = false;
        $this->applyAriaAttribute = $applyAriaAttribute;
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

        if ($this->applyAriaAttribute && $this->hasHelpText()) {
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
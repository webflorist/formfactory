<?php

namespace Webflorist\FormFactory\Components\Helpers;

use Webflorist\FormFactory\Components\FormControls\Contracts\FieldInterface;
use Webflorist\FormFactory\Components\FormControls\Contracts\FormControlInterface;
use Webflorist\FormFactory\Components\FormControls\RadioInput;
use Webflorist\FormFactory\Utilities\FormFactoryTools;
use Webflorist\HtmlFactory\Elements\Abstracts\Element;
use Webflorist\HtmlFactory\Elements\LabelElement;

class FieldLabel extends LabelElement
{
    /**
     * The field this FieldLabel belongs to.
     *
     * @var Element|FormControlInterface
     */
    public $field;

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
     * Signals the view, that this label should wrap a checkable field,
     * instead of being rendered bound after the field.
     *
     * @var bool
     */
    public $wrapCheckable = false;

    /**
     * Should the label include an indicator for required fields?
     *
     * @var bool
     */
    public $displayRequiredFieldIndicator = true;

    /**
     * FieldLabel constructor.
     *
     * @param Element|FieldInterface $field
     */
    public function __construct(FieldInterface $field)
    {
        parent::__construct();
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

    public function beforeDecoration()
    {

        if ($this->displayLabel) {

            // Perform auto-translation, if no label was manually set.
            if (!$this->hasLabel()) {
                $defaultValue = ucwords(FormFactoryTools::arrayStripString($this->field->getFieldName()));
                if ($this->field->is(RadioInput::class)) {
                    $defaultValue = ucwords($this->field->attributes->value);
                }
                $this->setText(
                    $this->field->performAutoTranslation($defaultValue)
                );
            }

            // Set auto-translation for placeholder.
            if ($this->field->attributes->isAllowed('placeholder') && !$this->field->attributes->isSet('placeholder')) {
                $this->field->placeholder(function () {
                    $defaultValue = $this->hasLabel() ? $this->getText() : null;
                    return $this->field->performAutoTranslation($defaultValue, 'Placeholder');
                });
            }

            $this->for($this->field->attributes->id);
            $this->content($this->getText());
            if ($this->displayRequiredFieldIndicator) {
                $this->appendContent(new RequiredFieldIndicator($this->field));
            }
        }

    }

     /**
     * Don't render output, if label should not be displayed.
     *
     * @param string $output
     */
    protected function manipulateOutput(string &$output)
    {
        if (!$this->displayLabel || !$this->hasLabel()) {
            $output = '';
        }
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

}
<?php

namespace Nicat\FormFactory\Decorators\Bulma\v0;

use Nicat\FormFactory\Components\Additional\FieldWrapper;
use Nicat\FormFactory\Components\FormControls\CheckboxInput;
use Nicat\FormFactory\Components\FormControls\RadioInput;
use Nicat\HtmlFactory\Decorators\Abstracts\Decorator;
use Nicat\HtmlFactory\Elements\DivElement;
use Nicat\HtmlFactory\Elements\SelectElement;

class StyleFieldWrapper extends Decorator
{

    /**
     * The element to be decorated.
     *
     * @var FieldWrapper
     */
    protected $element;

    /**
     * Returns an array of frontend-framework-ids, this decorator is specific for.
     *
     * @return string[]
     */
    public static function getSupportedFrameworks(): array
    {
        return [
            'bulma:0'
        ];
    }

    /**
     * Returns an array of class-names of elements, that should be decorated by this decorator.
     *
     * @return string[]
     */
    public static function getSupportedElements(): array
    {
        return [
            FieldWrapper::class
        ];
    }

    /**
     * Perform decorations on $this->element.
     */
    public function decorate()
    {
        $this->element->addClass('field');

        if (!is_null($this->element->field)) {

            // Add error-class to field, if field has errors.
            if ($this->element->field->hasErrors()) {
                $this->element->field->addClass('is-danger');
            }

            // Wrap the field itself within an additional div with class 'control'.
            $this->wrapControl();

        }

    }

    /**
     * Bulma wants each field separately wrapped with a div with class 'control'.
     */
    private function wrapControl()
    {
        // If a field is wrapped by a label (radios and checkboxes),
        // we wrap the label with a div.control.
        if ($this->element->field->labelMode === 'bound') {
            $this->element->label->wrap(
                (new DivElement())->addClass('control')
            );
            return;

        }

        // In all other cases, we wrap the field itself with a div with the corresponding class(es).
        $this->element->content->replaceChild(
            $this->element->field,
            (new DivElement())->addClass($this->getControlClasses())->content($this->element->field)
        );
    }

    /**
     * For select-boxes we also add the 'select' class.
     *
     * @return string
     */
    private function getControlClasses()
    {
        if ($this->element->field->is(SelectElement::class)) {
            return 'control select';
        }
        return 'control';
    }

}
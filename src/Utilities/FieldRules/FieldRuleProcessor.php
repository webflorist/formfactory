<?php

namespace Nicat\FormBuilder\Utilities\FieldRules;


use Nicat\FormBuilder\Components\Traits\CanHaveRules;
use Nicat\HtmlBuilder\Elements\Abstracts\Element;
use Nicat\HtmlBuilder\Elements\InputElement;
use Nicat\HtmlBuilder\Attributes\Traits\AllowsAcceptAttribute;
use Nicat\HtmlBuilder\Attributes\Traits\AllowsMaxAttribute;
use Nicat\HtmlBuilder\Attributes\Traits\AllowsMaxlengthAttribute;
use Nicat\HtmlBuilder\Attributes\Traits\AllowsMinAttribute;
use Nicat\HtmlBuilder\Attributes\Traits\AllowsPatternAttribute;
use Nicat\HtmlBuilder\Attributes\Traits\AllowsRequiredAttribute;
use Nicat\HtmlBuilder\Attributes\Traits\AllowsTypeAttribute;

/**
 * Applies laravel-rules to the field's attributes for browser-live-validation.
 *
 * Class FieldRuleProcessor
 * @package Nicat\FormBuilder
 */
class FieldRuleProcessor
{

    /**
     * @var Element|CanHaveRules
     */
    private $element;

    /**
     * Apply any rules for/to $element.
     *
     * @param Element|CanHaveRules $element
     */
    public static function process($element)
    {

        if ($element->hasRules()) {
            $rulesProcessor = new FieldRuleProcessor($element);
            foreach ($element->getRules() as $rule => $parameters) {
                $applyRulesMethod = 'apply' . studly_case($rule) . 'Rule';
                if (method_exists($rulesProcessor, $applyRulesMethod)) {
                    call_user_func([$rulesProcessor,$applyRulesMethod], $parameters);
                }
            }
        }
    }

    /**
     * RulesProcessor constructor.
     *
     * @param Element $element
     */
    private function __construct($element)
    {
        $this->element = $element;
    }

    /**
     * Applies 'required' rule.
     */
    private function applyRequiredRule()
    {
        /** @var AllowsRequiredAttribute $element */
        $element = $this->element;
        $element->required();
    }

    /**
     * Applies 'accepted' rule.
     *
     */
    private function applyAcceptedRule()
    {
        $this->applyRequiredRule();
    }

    /**
     * Applies 'not_numeric' rule.
     */
    private function applyNotNumericRule()
    {
        $this->applyPatternAttribute('\D+');
    }

    /**
     * Applies 'url' rule.
     */
    private function applyUrlRule()
    {
        /** @var AllowsTypeAttribute $element */
        $element = $this->element;
        $element->type('url');
    }

    /**
     * Applies 'active_url' rule.
     */
    private function applyActiveUrlRule()
    {
        $this->applyUrlRule();
    }

    /**
     * Applies 'alpha' rule.
     */
    private function applyAlphaRule()
    {
        $this->applyPatternAttribute('[a-zA-Z]+');
    }

    /**
     * Applies 'alpha_dash' rule.
     */
    private function applyAlphaDashRule()
    {
        $this->applyPatternAttribute('[a-zA-Z0-9_\-]+');
    }

    /**
     * Applies 'alpha_num' rule.
     *
     */
    private function applyAlphaNumRule()
    {
        $this->applyPatternAttribute('[a-zA-Z0-9]+');
    }

    /**
     * Applies 'between' rule.
     *
     * @param array $parameters
     */
    private function applyBetweenRule(array $parameters)
    {

        // For number-inputs we apply a min- and max-attributes.
        if ($this->element->is(InputElement::class) && ($this->element->attributes->type === 'number')) {
            $this->applyMinAttribute($parameters[0]);
            $this->applyMaxAttribute($parameters[1]);
            return;
        }

        // For all others, we apply pattern- and maxlength-attributes.
        $this->applyPatternAttribute('.{' . $parameters[0] . ',' . $parameters[1] . '}',true);
        $this->applyMaxlengthAttribute($parameters[1]);

    }

    /**
     * Applies 'in' rule.
     *
     * @param array $parameters
     */
    private function applyInRule(array $parameters)
    {
        $parameters = (sizeof($parameters) == 1) ? $parameters[0] : '(' . join('|', $parameters) . ')';
        $this->applyPatternAttribute('^' . $parameters . '$');
    }

    /**
     * Applies 'required' rule.
     *
     * @param array $parameters
     */
    private function applyMaxRule(array $parameters)
    {

        // For number-inputs we apply a max-attribute.
        if ($this->element->is(InputElement::class) && ($this->element->attributes->type === 'number')) {
            $this->applyMaxAttribute($parameters[0]);
            return;
        }

        // For all others we apply the maxlength attribute.
        $this->applyMaxlengthAttribute($parameters[0]);

    }

    /**
     * Applies 'min' rule.
     *
     * @param array $parameters
     */
    private function applyMinRule(array $parameters)
    {

        // For number-inputs we apply a min-attribute.
        if ($this->element->is(InputElement::class) && ($this->element->attributes->type === 'number')) {
            $this->applyMinAttribute($parameters[0]);
            return;
        }

        // For all others we apply the pattern attribute.
        $this->applyPatternAttribute(".{" . $parameters[0] . ",}", true);
    }

    /**
     * Applies 'not_in' rule.
     *
     * @param array $parameters
     */
    private function applyNotInRule(array $parameters)
    {
        $this->applyPatternAttribute('(?:(?!^' . join('$|^', $parameters) . '$).)*');
    }

    /**
     * Applies 'numeric' rule.
     *
     * @param array $parameters
     */
    private function applyNumericRule(array $parameters)
    {
        /** @var AllowsTypeAttribute $element */
        $element = $this->element;
        $element->type('number');
        $this->applyPatternAttribute('[+-]?\d*\.?\d+');
    }

    /**
     * Applies 'mimes' rule.
     *
     * @param array $parameters
     */
    private function applyMimesRule(array $parameters)
    {
        /** @var AllowsAcceptAttribute $element */
        $element = $this->element;
        if (array_search('jpeg', $parameters) !== false) {
            array_push($parameters, 'jpg');
        }
        $element->accept('.' . implode(',.', $parameters));
    }

    /**
     * Applies a pattern-attribute.
     *
     * @param string $pattern
     * @param bool $append
     */
    private function applyPatternAttribute(string $pattern, $append = false)
    {
        if ($this->element->attributes->isAllowed('pattern')) {

            /** @var AllowsPatternAttribute $element */
            $element = $this->element;

            // Append to existing pattern, if $append=true.
            if ($append && $element->attributes->isSet('pattern')) {
                $pattern = $element->attributes->pattern . $pattern;
            }

            $element->pattern($pattern);

        }
    }

    /**
     * Applies a maxlength-attribute.
     *
     * @param string $maxlength
     */
    private function applyMaxlengthAttribute(string $maxlength)
    {
        if ($this->element->attributes->isAllowed('maxlength')) {
            /** @var AllowsMaxlengthAttribute $element */
            $element = $this->element;
            $element->maxlength($maxlength);
        }
    }

    /**
     * Applies a max-attribute.
     *
     * @param int $value
     */
    private function applyMaxAttribute(int $value)
    {
        if ($this->element->attributes->isAllowed('max')) {
            /** @var AllowsMaxAttribute $element */
            $element = $this->element;
            $element->max($value);
        }
    }

    /**
     * Applies a min-attribute.
     *
     * @param int $value
     */
    private function applyMinAttribute(int $value)
    {
        if ($this->element->attributes->isAllowed('min')) {
            /** @var AllowsMinAttribute $element */
            $element = $this->element;
            $element->min($value);
        }
    }

}
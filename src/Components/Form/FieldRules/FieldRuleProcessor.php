<?php

namespace Webflorist\FormFactory\Components\Form\FieldRules;

use Illuminate\Support\Str;
use Webflorist\FormFactory\Components\FormControls\Contracts\FieldInterface;
use Webflorist\HtmlFactory\Elements\Abstracts\Element;
use Webflorist\HtmlFactory\Elements\InputElement;
use Webflorist\HtmlFactory\Attributes\Traits\AllowsAcceptAttribute;
use Webflorist\HtmlFactory\Attributes\Traits\AllowsMaxAttribute;
use Webflorist\HtmlFactory\Attributes\Traits\AllowsMaxlengthAttribute;
use Webflorist\HtmlFactory\Attributes\Traits\AllowsMinAttribute;
use Webflorist\HtmlFactory\Attributes\Traits\AllowsPatternAttribute;
use Webflorist\HtmlFactory\Attributes\Traits\AllowsRequiredAttribute;
use Webflorist\HtmlFactory\Attributes\Traits\AllowsTypeAttribute;

/**
 * Applies laravel-rules to the field's attributes for browser-live-validation.
 *
 * Class FieldRuleProcessor
 * @package Webflorist\FormFactory
 */
class FieldRuleProcessor
{

    /**
     * @var FieldInterface|Element
     */
    private $field;

    /**
     * RulesProcessor constructor.
     *
     * @param FieldInterface $field
     */
    private function __construct(FieldInterface $field)
    {
        $this->field = $field;
    }

    /**
     * Apply any rules for/to $field.
     *
     * @param FieldInterface $field
     */
    public static function process(FieldInterface $field)
    {

        if ($field->hasRules()) {
            $rulesProcessor = new FieldRuleProcessor($field);
            foreach ($field->getRules() as $rule => $parameters) {
                $applyRulesMethod = 'apply' . Str::studly($rule) . 'Rule';
                if (method_exists($rulesProcessor, $applyRulesMethod)) {
                    call_user_func([$rulesProcessor,$applyRulesMethod], $parameters);
                }
            }
        }
    }

    /**
     * Applies 'required' rule.
     */
    private function applyRequiredRule()
    {
        /** @var AllowsRequiredAttribute $field */
        $field = $this->field;
        $field->required();
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
        /** @var AllowsTypeAttribute $field */
        $field = $this->field;
        $field->type('url');
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
        if ($this->field->is(InputElement::class) && ($this->field->attributes->type === 'number')) {
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
        if ($this->field->is(InputElement::class) && ($this->field->attributes->type === 'number')) {
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
        if ($this->field->is(InputElement::class) && ($this->field->attributes->type === 'number')) {
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
        /** @var AllowsTypeAttribute $field */
        $field = $this->field;
        $field->type('number');
        $this->applyPatternAttribute('[+-]?\d*\.?\d+');
    }

    /**
     * Applies 'mimes' rule.
     *
     * @param array $parameters
     */
    private function applyMimesRule(array $parameters)
    {
        /** @var AllowsAcceptAttribute $field */
        $field = $this->field;
        if (array_search('jpeg', $parameters) !== false) {
            array_push($parameters, 'jpg');
        }
        $field->accept('.' . implode(',.', $parameters));
    }

    /**
     * Applies a pattern-attribute.
     *
     * @param string $pattern
     * @param bool $append
     */
    private function applyPatternAttribute(string $pattern, $append = false)
    {
        if ($this->field->attributes->isAllowed('pattern')) {

            /** @var AllowsPatternAttribute $field */
            $field = $this->field;

            // Append to existing pattern, if $append=true.
            if ($append && $field->attributes->isSet('pattern')) {
                $pattern = $field->attributes->pattern . $pattern;
            }

            $field->pattern($pattern);

        }
    }

    /**
     * Applies a maxlength-attribute.
     *
     * @param string $maxlength
     */
    private function applyMaxlengthAttribute(string $maxlength)
    {
        if ($this->field->attributes->isAllowed('maxlength')) {
            /** @var AllowsMaxlengthAttribute $field */
            $field = $this->field;
            $field->maxlength($maxlength);
        }
    }

    /**
     * Applies a max-attribute.
     *
     * @param int $value
     */
    private function applyMaxAttribute(int $value)
    {
        if ($this->field->attributes->isAllowed('max')) {
            /** @var AllowsMaxAttribute $field */
            $field = $this->field;
            $field->max($value);
        }
    }

    /**
     * Applies a min-attribute.
     *
     * @param int $value
     */
    private function applyMinAttribute(int $value)
    {
        if ($this->field->attributes->isAllowed('min')) {
            /** @var AllowsMinAttribute $field */
            $field = $this->field;
            $field->min($value);
        }
    }

}
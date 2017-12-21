<?php

namespace Nicat\FormBuilder\Decorators\General;

use Nicat\FormBuilder\Elements\NumberInputElement;
use Nicat\FormBuilder\Elements\TextInputElement;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;
use Nicat\HtmlBuilder\Elements\Abstracts\Element;
use Nicat\HtmlBuilder\Elements\Abstracts\InputElement;
use Nicat\HtmlBuilder\Elements\TextareaElement;

/**
 * Applies laravel-rules to the field's attributes for browser-live-validation.
 *
 * Class IndicateRequiredFields
 * @package Nicat\FormBuilder\Decorators\General
 */
class ApplyRules extends Decorator
{

    /**
     * Returns an array of frontend-framework-ids, this decorator is specific for.
     * Returning an empty array means all frameworks are supported.
     *
     * @return string[]
     */
    public static function getSupportedFrameworks(): array
    {
        return [];
    }

    /**
     * Returns an array of class-names of elements, that should be decorated by this decorator.
     *
     * @return string[]
     */
    public static function getSupportedElements(): array
    {
        return [
            TextInputElement::class,
            NumberInputElement::class,
            TextareaElement::class
        ];
    }

    /**
     * Decorates the element.
     *
     * @param Element $element
     */
    public static function decorate(Element $element)
    {
        if ($element->hasRules()) {
            foreach ($element->getRules() as $rule => $parameters) {
                $applyRulesMethod = 'apply'.studly_case($rule).'Rule';
                if (method_exists(static::class, $applyRulesMethod)) {
                    static::$applyRulesMethod($element,$parameters);
                }
            }
        }
    }

    /**
     * Applies 'required' rule.
     *
     * @param Element $element
     * @param array $parameters
     */
    private static function applyRequiredRule(Element $element, array $parameters) {
        $element->required();
    }

    /**
     * Applies 'accepted' rule.
     *
     * @param Element $element
     * @param array $parameters
     */
    private static function applyAcceptedRule(Element $element,array $parameters) {
        static::applyRequiredRule($element,$parameters);
    }

    /**
     * Applies 'not_numeric' rule.
     *
     * @param Element $element
     * @param array $parameters
     */
    private static function applyNotNumericRule(Element $element,array $parameters) {
        if($element->attributes->isAllowed('pattern')) {
            $element->pattern('\D+');            
        }
    }

    /**
     * Applies 'url' rule.
     *
     * @param Element $element
     * @param array $parameters
     */
    private static function applyUrlRule(Element $element,array $parameters) {
        $element->type('url');
    }

    /**
     * Applies 'active_url' rule.
     *
     * @param Element $element
     * @param array $parameters
     */
    private static function applyActiveUrlRule(Element $element,array $parameters) {
        static::applyUrlRule($element,$parameters);
    }

    /**
     * Applies 'alpha' rule.
     *
     * @param Element $element
     * @param array $parameters
     */
    private static function applyAlphaRule(Element $element,array $parameters) {
        if($element->attributes->isAllowed('pattern')) {
            $element->pattern('[a-zA-Z]+');
        }
    }

    /**
     * Applies 'alpha_dash' rule.
     *
     * @param Element $element
     * @param array $parameters
     */
    private static function applyAlphaDashRule(Element $element,array $parameters) {
        if($element->attributes->isAllowed('pattern')) {
            $element->pattern('[a-zA-Z0-9_\-]+');
        }
    }

    /**
     * Applies 'alpha_num' rule.
     *
     * @param Element $element
     * @param array $parameters
     */
    private static function applyAlphaNumRule(Element $element,array $parameters) {
        if($element->attributes->isAllowed('pattern')) {
            $element->pattern('[a-zA-Z0-9]+');
        }
    }

    /**
     * Applies 'between' rule.
     *
     * @param Element $element
     * @param array $parameters
     */
    private static function applyBetweenRule(Element $element,array $parameters) {
        if (is_a($element, InputElement::class)) {
            if ($element->attributes->getValue('type') === 'number') {
                $element->min($parameters[0]);
                $element->max($parameters[1]);
            } else {
                $element->pattern(
                    $element->attributes->getValue('pattern') .
                    '.{' . $parameters[0] . ',' . $parameters[1] . '}'
                );
                $element->maxlength($parameters[1]);
            }
        } else if (is_a($element, TextareaElement::class)) {
            $element->maxlength($parameters[1]);
        }
    }

    /**
     * Applies 'in' rule.
     *
     * @param Element $element
     * @param array $parameters
     */
    private static function applyInRule(Element $element,array $parameters) {
        $parameters = (sizeof($parameters) == 1) ? $parameters[0] : '(' . join('|', $parameters) . ')';
        $element->pattern('^' . $parameters . '$');
    }

    /**
     * Applies 'required' rule.
     *
     * @param Element $element
     * @param array $parameters
     */
    private static function applyMaxRule(Element $element,array $parameters) {
        if (is_a($element, InputElement::class)) {
            if ($element->attributes->getValue('type') === 'number') {
                $element->max($parameters[0]);
            } else {
                $element->maxlength($parameters[0]);
            }
        } else if (is_a($element, TextareaElement::class)) {
            $element->maxlength($parameters[0]);
        }
    }

    /**
     * Applies 'min' rule.
     *
     * @param Element $element
     * @param array $parameters
     */
    private static function applyMinRule(Element $element,array $parameters) {
        if ($element->attributes->getValue('type') === 'number') {
            $element->min($parameters[0]);
        } else {
            if ($element->attributes->isSet('pattern')) {
                $element->pattern(
                    $element->attributes->getValue('pattern') .
                    ".{" . $parameters[0] . ",}"
                );
            } else {
                $element->pattern(".{" . $parameters[0] . ",}");
            }
        }
    }

    /**
     * Applies 'not_in' rule.
     *
     * @param Element $element
     * @param array $parameters
     */
    private static function applyNotInRule(Element $element,array $parameters) {
        $element->pattern('(?:(?!^' . join('$|^', $parameters) . '$).)*');
    }

    /**
     * Applies 'numeric' rule.
     *
     * @param Element $element
     * @param array $parameters
     */
    private static function applyNumericRule(Element $element,array $parameters) {
        $element->type('number');
        $element->pattern('[+-]?\d*\.?\d+');
    }

    /**
     * Applies 'mimes' rule.
     *
     * @param Element $element
     * @param array $parameters
     */
    private static function applyMimesRule(Element $element,array $parameters) {
        if (array_search('jpeg', $parameters) !== false) {
            array_push($parameters, 'jpg');
        }
        $element->accept('.' . implode(',.', $parameters));
    }

}
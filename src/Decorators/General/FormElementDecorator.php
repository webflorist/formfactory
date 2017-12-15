<?php

namespace Nicat\FormBuilder\Decorators\General;

use Nicat\FormBuilder\Elements\FormElement;
use Nicat\FormBuilder\Elements\HiddenInputElement;
use Nicat\HtmlBuilder\Decorators\Abstracts\Decorator;
use Nicat\HtmlBuilder\Nodes\Abstracts\Node;
use URL;

class FormElementDecorator extends Decorator
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
     * Returns an array of class-names of nodes, that should be decorated by this decorator.
     *
     * @return string[]
     */
    public static function getSupportedNodes(): array
    {
        return [
            FormElement::class
        ];
    }

    /**
     * Decorates the node.
     *
     * @param Node $node
     */
    public static function decorate(Node $node)
    {
        /** @var FormElement $node */
        self::appendCSRFToken($node);
        self::setDefaultAction($node);
    }

    /**
     * Append hidden input tag with CSRF-token (except for forms with a GET-method),
     * if $form->generateToken is not set to false.
     * @param FormElement $form
     */
    private static function appendCSRFToken(FormElement $form)
    {
        if ($form->generateToken && $form->attributes->getValue('method') !== 'get') {
            $form->appendContent(
                (new HiddenInputElement())->name('_token')->value(csrf_token())
            );
        }
    }

    /**
     * Set default action to current URL, if none was set.
     *
     * @param FormElement $form
     */
    private static function setDefaultAction(FormElement $form)
    {
        if (!$form->attributes->isSet('action')) {
            $form->action(URL::current());
        }
    }
}
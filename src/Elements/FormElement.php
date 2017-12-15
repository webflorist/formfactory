<?php

namespace Nicat\FormBuilder\Elements;

class FormElement extends \Nicat\HtmlBuilder\Elements\FormElement
{

    /**
     * Automatically generate hidden CSRF-token-tag (enabled by default)?
     *
     * @var bool
     */
    protected $generateToken = true;

    /**
     * Display legend regarding mandatory fields?
     *
     * @var bool
     */
    protected $displayMandatoryFieldsLegend = true;

    /**
     * Overwritten to apply certain modifications.
     *
     * @return string
     */
    public function render(): string
    {
        $this->appendCSRFToken();
        $this->setDefaultAction();

        $html = parent::render();

        // We remove the closing tag, since FormBuilder closes the form-tag via method close().
        return str_before($html, '</form>');
    }

    /**
     * Enable / disable automatic generation of hidden CSRF-token-tag.
     * (Enabled by default)
     *
     * @param boolean $generateToken
     * @return $this
     */
    public function generateToken(bool $generateToken = true)
    {
        $this->generateToken = $generateToken;
        return $this;
    }

    /**
     * Append hidden input tag with CSRF-token (except for forms with a GET-method),
     * if $this->>generateToken is not set to false.
     *
     */
    protected function appendCSRFToken()
    {
        if ($this->generateToken && $this->attributes->getValue('method') !== 'get') {
            $this->appendContent(
                (new HiddenInputElement())->name('_token')->value(csrf_token())
            );
        }
    }

    /**
     * Set default action to current URL, if none was set.
     */
    private function setDefaultAction()
    {
        if (!$this->attributes->isSet('action')) {
            $this->action(\URL::current());
        }
    }
}
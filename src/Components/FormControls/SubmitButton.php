<?php

namespace Webflorist\FormFactory\Components\FormControls;

use Webflorist\FormFactory\Components\FormControls\Traits\FormControlTrait;
use Webflorist\FormFactory\Components\FormControls\Contracts\FormControlInterface;
use Webflorist\FormFactory\Components\FormControls\Contracts\AutoTranslationInterface;
use Webflorist\FormFactory\Components\FormControls\Traits\AutoTranslationTrait;
use Webflorist\HtmlFactory\Components\SubmitButtonComponent;
use Webflorist\HtmlFactory\Components\Traits\HasContext;

class SubmitButton
    extends SubmitButtonComponent
    implements FormControlInterface, AutoTranslationInterface
{
    use FormControlTrait,
        AutoTranslationTrait,
        HasContext;

    /**
     * SubmitButton constructor.
     *
     * @param string $name
     */
    public function __construct(string $name = 'submit')
    {
        parent::__construct();
        $this->name($name);
        $this->setupFormControl();
    }

    /**
     * Gets called after applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function afterDecoration()
    {
        parent::afterDecoration();
        $this->processFormControl();
    }

    protected function beforeDecoration()
    {
        parent::beforeDecoration();
        if (!$this->content->hasContent()) {
            $this->content(
                $this->performAutoTranslation(ucwords($this->attributes->name))
            );
        }
    }

    /**
     * Returns the base translation-key for auto-translations for this object.
     *
     * @return string
     */
    public function getAutoTranslationKey(): string
    {
        // If the "name"-attribute is set, we use that as the translation-key.
        if ($this->attributes->isSet('name')) {
            return $this->attributes->name;
        }

        // If the button has a "context", we use that as the translation-key.
        if ($this->hasContext()) {
            return $this->getContext();
        }

        // As default-suffix, we use the string 'submit'.
        return 'submit';
    }

    public function confirmDialog(?string $title=null, ?string $text=null, ?string $confirmButtonText=null, ?string $cancelButtonText=null) {

        if (is_null($title)) {
            $title = trans('webflorist-formfactory::formfactory.default_confirm_title');
        }
        if (is_null($text)) {
            $text = trans('webflorist-formfactory::formfactory.default_confirm_text');
        }
        if (is_null($confirmButtonText)) {
            $confirmButtonText = trans('webflorist-formfactory::formfactory.default_confirm_button_text');
        }
        if (is_null($cancelButtonText)) {
            $cancelButtonText = trans('webflorist-formfactory::formfactory.default_cancel_button_text');
        }
        $title = str_replace("'", "\'", $title);
        $text = str_replace("'", "\'", $text);
        $confirmButtonText = str_replace("'", "\'", $confirmButtonText);
        $cancelButtonText = str_replace("'", "\'", $cancelButtonText);
        $this->vOnClick("displayConfirmDialog('$title', '$text', '$confirmButtonText', '$cancelButtonText')", ['prevent']);
        return $this;
    }
}
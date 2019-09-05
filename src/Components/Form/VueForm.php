<?php

namespace Webflorist\FormFactory\Components\Form;

use Webflorist\FormFactory\Components\FormControls\TextInput;
use Webflorist\FormFactory\Components\Helpers\ErrorContainer;
use Webflorist\FormFactory\Exceptions\MandatoryOptionMissingException;
use Webflorist\FormFactory\Exceptions\MissingVueDependencyException;
use Webflorist\FormFactory\Vue\FormFactoryFormRequestTrait;
use Webflorist\FormFactory\Vue\VueInstanceGenerator;
use Webflorist\HtmlFactory\Components\AlertComponent;
use Webflorist\HtmlFactory\Elements\DivElement;
use Webflorist\HtmlFactory\Elements\TemplateElement;

class VueForm extends Form
{

    /**
     * Gets set to true, if a vue instance has been generated for this form.
     *
     * @var bool
     */
    private $vueInstanceGenerated = false;

    /**
     * Returns the VueInstance object for this form.
     *
     * To avoid duplicate rendering of the vue-instance,
     * this function will return null, if it was called before.
     * Set first parameter to false, to avoid this.
     *
     * @param bool $returnNullIfAlreadyGenerated
     * @return \Webflorist\VueFactory\VueInstance|null
     */
    public function getVueInstance($returnNullIfAlreadyGenerated=true)
    {
        if ($returnNullIfAlreadyGenerated && $this->vueInstanceGenerated) {
            return null;
        }
        $this->vueInstanceGenerated = true;
        return (new VueInstanceGenerator($this))->getVueInstance();
    }

    /**
     * Apply some vue-specific modifications.
     *
     * @throws MandatoryOptionMissingException
     */
    protected function beforeDecoration()
    {

        //$this->checkVueDependencies();
        $this->applyVueModifications();

        parent::beforeDecoration();
    }

    /**
     * Checks various dependencies for vue-functionality.
     *
     * @throws MissingVueDependencyException
     */
    private function checkVueDependencies()
    {
        $formId = $this->getId();
        if (is_null($this->requestObject)) {
            throw new MissingVueDependencyException("No form request object was set for the VueForm with id '$formId'. Supply a form request object via Form::vOpen()->requestObject().'");
        }
        if (array_search(FormFactoryFormRequestTrait::class, class_uses_recursive($this->requestObject)) === false) {
            throw new MissingVueDependencyException("The form request object '$this->requestObject' must use the 'FormFactoryFormRequestTrait' to enable vue-functionality for it's form.");
        }
    }

    /**
     * Apply various modifications to this Form, if vue is enabled.
     */
    private function applyVueModifications()
    {
        $this->vOn('submit', 'submitForm', ['prevent']);
        $this->appendContent(
            (new AlertComponent('danger'))
                ->appendContent(
                    (new DivElement())->vFor("error in generalErrors")->content('{{ error }}')
                )
                ->vIf("generalErrors.length")
                ->wrap(new TemplateElement())
        );
        $this->appendContent(
            (new AlertComponent('success'))
                ->appendContent('{{ successMessage }}')
                ->vIf("successMessage.length")
                ->wrap(new TemplateElement())
        );
    }

    /**
     * Appends the captcha-field to this form.
     *
     * (Overwritten to append the captcha-field,
     * even if the limit is not reached.)
     */
    protected function appendCaptchaField()
    {
        $this->appendContent($this->getCaptchaField());
    }

    /**
     * Returns the TextInput element for the captcha.
     *
     * @return TextInput
     */
    protected function getCaptchaField(): TextInput
    {
        $captchaField = parent::getCaptchaField();

        $captchaField->label->setText('{{ captchaQuestion }}');
        $captchaField->wrapper->vIf('captchaQuestion');
        $captchaField->wrapper->wrap(new TemplateElement());

        return $captchaField;
    }

    /**
     * VueForms might not need the csrf-token (configurable).
     */
    protected function appendCSRFToken()
    {
        if (config('formfactory.vue.generate_csrf_token') !==false) {
            parent::appendCSRFToken();
        }
    }


}

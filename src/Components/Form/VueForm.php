<?php

namespace Nicat\FormFactory\Components\Form;

use Nicat\FormFactory\Components\FormControls\TextInput;
use Nicat\FormFactory\Components\Helpers\ErrorContainer;
use Nicat\FormFactory\Exceptions\MandatoryOptionMissingException;
use Nicat\FormFactory\Exceptions\MissingVueDependencyException;
use Nicat\FormFactory\Vue\FormFactoryFormRequestTrait;
use Nicat\FormFactory\Vue\VueInstanceGenerator;
use Nicat\HtmlFactory\Components\AlertComponent;
use Nicat\HtmlFactory\Elements\DivElement;
use Nicat\HtmlFactory\Elements\TemplateElement;

class VueForm extends Form
{

    /**
     * Gets set to true, if a vue instance has been generated for this form.
     *
     * @var bool
     */
    public $vueInstanceGenerated = false;

    /**
     * Returns the VueInstance object for this form.
     *
     * @return \Nicat\VueFactory\VueInstance
     */
    public function getVueInstance()
    {
        return (new VueInstanceGenerator($this))->getVueInstance();
    }

    /**
     * Apply some vue-specific modifications.
     *
     * @throws MandatoryOptionMissingException
     * @throws MissingVueDependencyException
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
            (new ErrorContainer())
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

}
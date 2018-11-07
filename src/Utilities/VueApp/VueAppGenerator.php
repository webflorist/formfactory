<?php

namespace Nicat\FormFactory\Utilities\VueApp;

use Nicat\FormFactory\Components\Form\Form;
use Nicat\VueFactory\VueInstance;
use stdClass;

/**
 * Generates a Vue-app from a Form.
 *
 * Class VueAppGenerator
 * @package Nicat\FormFactory
 */
class VueAppGenerator
{

    /**
     * The Form to be vueified.
     *
     * @var Form
     */
    private $form;

    /**
     * The VueInstance we will build.
     *
     * @var VueInstance
     */
    private $vueInstance;

    /**
     * The "fields" data-object.
     *
     * @var stdClass
     */
    private $fieldData;

    /**
     * Form constructor.
     *
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
        $this->vueInstance = new VueInstance('#' . $this->form->getId());
        $this->fieldData = new stdClass();
        $this->parseFormControls();
        $this->vueInstance->addData('fields', $this->fieldData);
        $this->vueInstance->addMethod('fieldHasError','function (fieldName) {return this.fields[fieldName].errors.length > 0;}');
        $this->addComputeErrorFlags();
    }

    /**
     * Parses form-controls and adds structured field-info to $this->fieldData.
     */
    private function parseFormControls()
    {
        foreach ($this->form->getFormControls() as $control) {
            if ($control->isAField()) {
                $this->fieldData->{$control->attributes->name} = new Field($control, $this->fieldData);
            }
        }
    }

    /**
     * Returns the VueInstance.
     *
     * @return VueInstance
     */
    public function getVueInstance(): VueInstance
    {
        return $this->vueInstance;
    }

    /**
     * Adds computed boolean properties for each flag.
     * indicating a current error in any field.
     */
    private function addComputeErrorFlags()
    {
        $jsStatements = [];
        foreach ($this->fieldData as $fieldName => $field) {
            $jsStatements[] = "this.fields['$fieldName'].errors.length > 0";
        }
        $jsStatements = implode(' || ',$jsStatements);

        $this->vueInstance->addComputed('hasErrors', "function () {return $jsStatements;}");
    }

}
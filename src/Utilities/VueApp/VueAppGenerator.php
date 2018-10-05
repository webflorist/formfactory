<?php

namespace Nicat\FormFactory\Utilities\VueApp;

use Nicat\FormFactory\Utilities\ComponentLists;
use Nicat\FormFactory\Utilities\Forms\FormInstance;
use Nicat\VueFactory\VueInstance;
use stdClass;

/**
 * Generates a Vue-app from a FormInstance.
 *
 * Class VueAppGenerator
 * @package Nicat\FormFactory
 */
class VueAppGenerator
{

    /**
     * The FormInstance to be vueified.
     *
     * @var FormInstance
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
    private $fields;

    /**
     * FormInstance constructor.
     *
     * @param FormInstance $form
     */
    public function __construct(FormInstance $form)
    {
        $this->form = $form;
        $this->vueInstance = new VueInstance('#' . $this->form->getId());
        $this->fields = new stdClass();
        $this->parseFormControls();
        $this->vueInstance->addData('fields', $this->fields);
        $this->addComputedErrorFlag();
    }

    /**
     * Parses form-controls and adds structured field-info to $this->fields.
     */
    private function parseFormControls()
    {
        foreach ($this->form->getFormControls() as $control) {
            if (array_search(get_class($control),ComponentLists::fields()) !== false) {
                new Field($control, $this->fields);
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
     * Adds a computed boolean attribute
     * indicating a current error in any field.
     */
    private function addComputedErrorFlag()
    {
        $jsStatements = [];
        foreach ($this->fields as $fieldName => $field) {
            $jsStatements[] = "this.fields.$fieldName.errors.length > 0";
        }
        $jsStatements = implode(' || ',$jsStatements);

        $this->vueInstance->addComputed('hasErrors', "function () {return $jsStatements;}");
    }

}
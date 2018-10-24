<?php

namespace Nicat\FormFactory\Utilities\Forms;

use Nicat\FormFactory\Exceptions\FormInstanceNotFoundException;
use Nicat\FormFactory\Exceptions\OpenElementNotFoundException;

/**
 * Manages FormInstances.
 *
 * Class FormInstanceManager
 * @package Nicat\FormFactory
 */
class FormInstanceManager
{

    /**
     * Array of FormInstance Objects to manage.
     *
     * @var FormInstance[]
     */
    private $forms = [];

    /**
     * Adds a form.
     *
     * @param FormInstance $form
     */
    public function addForm(FormInstance $form)
    {
        $this->forms[] = $form;
    }

    /**
     * Returns the currently open Form-element.
     *
     * @return FormInstance
     * @throws OpenElementNotFoundException
     */
    public function getOpenForm(): FormInstance
    {
        /** @var FormInstance $lastFormInstance */
        $lastFormInstance = end($this->forms);
        reset($this->forms);

        if (($lastFormInstance === false) || !$lastFormInstance->isOpen()) {
            throw new OpenElementNotFoundException('FormFactory could not find a currently open Form-element. This is probably due to generating a form-field with FormFactory without opening a form using Form::open() before that.');
        }

        return $lastFormInstance;
    }

    /**
     * Returns the FormInstance with the stated ID.
     *
     * @param string $id
     * @return FormInstance
     * @throws FormInstanceNotFoundException
     */
    public function getForm(string $id)
    {
        foreach ($this->forms as $form) {
            if ($form->getId() === $id) {
                return $form;
            }
        }

        throw new FormInstanceNotFoundException('FormFactory could not find a form-instance with id "$id". This is probably due to generating a form-field with FormFactory without opening a form using Form::open() before that.');
    }

    /**
     * Is a form currently open?
     *
     * @return bool
     */
    public function hasOpenForm()
    {
        try {
            $this->getOpenForm();
        } catch (OpenElementNotFoundException $e) {
            return false;
        }
        return true;
    }

    /**
     * Returns the FormInstance, that owns the stated field.
     *
     * @param string $id
     * @return FormInstance
     * @throws FormInstanceNotFoundException
     */
    public function getFormInstanceOfField(string $id)
    {
        foreach ($this->forms as $form) {
            if ($form->getId() === $id) {
                return $form;
            }
        }

        throw new FormInstanceNotFoundException('FormFactory could not find a form-instance with id "$id". This is probably due to generating a form-field with FormFactory without opening a form using Form::open() before that.');
    }

}
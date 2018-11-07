<?php

namespace Nicat\FormFactory\Utilities;

use Nicat\FormFactory\Components\Form\Form;
use Nicat\FormFactory\Exceptions\FormNotFoundException;
use Nicat\FormFactory\Exceptions\OpenElementNotFoundException;

/**
 * Manages Forms.
 *
 * Class FormManager
 * @package Nicat\FormFactory
 */
class FormManager
{

    /**
     * Array of Form Objects to manage.
     *
     * @var Form[]
     */
    private $forms = [];

    /**
     * Adds a form.
     *
     * @param Form $form
     */
    public function addForm(Form $form)
    {
        $this->forms[] = $form;
    }

    /**
     * Returns the currently open Form-element.
     *
     * @return Form
     * @throws OpenElementNotFoundException
     */
    public function getOpenForm(): Form
    {
        /** @var Form $lastForm */
        $lastForm = end($this->forms);
        reset($this->forms);

        if (($lastForm === false) || !$lastForm->isOpen()) {
            throw new OpenElementNotFoundException('FormFactory could not find a currently open Form-element. This is probably due to generating a form-field with FormFactory without opening a form using Form::open() before that.');
        }

        return $lastForm;
    }

    /**
     * Returns the Form with the stated ID.
     *
     * @param string $id
     * @return Form
     * @throws FormNotFoundException
     */
    public function getForm(string $id)
    {
        foreach ($this->forms as $form) {
            if ($form->getId() === $id) {
                return $form;
            }
        }

        throw new FormNotFoundException('FormFactory could not find a form with id "$id". This is probably due to generating a form-field with FormFactory without opening a form using Form::open() before that.');
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
     * Returns the Form, that owns the stated field.
     *
     * @param string $id
     * @return Form
     * @throws FormNotFoundException
     */
    public function getFormOfField(string $id)
    {
        foreach ($this->forms as $form) {
            if ($form->getId() === $id) {
                return $form;
            }
        }

        throw new FormNotFoundException('FormFactory could not find a form-instance with id "$id". This is probably due to generating a form-field with FormFactory without opening a form using Form::open() before that.');
    }

}
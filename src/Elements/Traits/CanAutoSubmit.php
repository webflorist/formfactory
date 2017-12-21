<?php

namespace Nicat\FormBuilder\Elements\Traits;

use Nicat\FormBuilder\FormBuilder;
use Nicat\FormBuilder\FormBuilderTools;

trait CanAutoSubmit
{

    /**
     * Enables automatic submission of form,
     * when 'change' event is fired.
     *
     * @return $this
     */
    public function autoSubmit() {
        $this->data('autosubmit','onChange');
        return $this;
    }

}
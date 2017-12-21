<?php

namespace Nicat\FormBuilder\Elements\Traits;

use Nicat\FormBuilder\FormBuilder;
use Nicat\FormBuilder\FormBuilderTools;

trait CanPerformAjaxValidation
{

    /**
     * Set ajaxValidation-mode.
     *
     * Can be 'onChange' or 'onKeyup' .
     * Only works, if ajax_validation is enabled in formbuilder-config.
     *
     * @param bool|string $ajaxValidation : 'onChange' or 'onKeyup'
     * @return $this
     */
    public function ajaxValidation($ajaxValidation = 'onChange')
    {
        // We only do this, if ajax-validation is generally enabled in the config.
        if (config('formbuilder.ajax_validation.enabled')) {

            // Set data-attribute for ajax-validation.
            $this->data('ajaxvalidation', $ajaxValidation);

        }

        return $this;
    }

}
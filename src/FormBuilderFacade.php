<?php

namespace Nicat\FormBuilder;

use Illuminate\Support\Facades\Facade;

class FormBuilderFacade extends Facade {

    /**
     * Static access-proxy for the FormBuilder
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return FormBuilder::class; }

}
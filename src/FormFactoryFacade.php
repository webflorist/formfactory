<?php

namespace Webflorist\FormFactory;

use Illuminate\Support\Facades\Facade;

class FormFactoryFacade extends Facade {

    /**
     * Static access-proxy for the FormFactory
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return FormFactory::class; }

}
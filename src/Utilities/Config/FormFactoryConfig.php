<?php

namespace Nicat\FormFactory\Utilities\Config;

/**
 * This class provides some static functions
 * that return various FormFactory related configs.
 *
 * Class FormFactory
 * @package Nicat\FormFactory
 *
 */
class FormFactoryConfig
{

    /**
     * Is Vue support enabled in the FormFactory config?
     *
     * @return bool
     */
    public static function isVueEnabled() : bool
    {
        return (config('formfactory.vue.enabled') === true) ? true : false;
    }

}
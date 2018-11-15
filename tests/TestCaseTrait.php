<?php

namespace FormFactoryTests;

trait TestCaseTrait
{

    protected $decorators = [];
    protected $vueEnabled = false;
    protected $openVueForm = false;

    private function setUpConfig($app)
    {
        $app['config']->set('htmlfactory.decorators', $this->decorators);
        $app['config']->set('formfactory.vue.enabled', $this->vueEnabled);
    }


}
<?php

namespace FormFactoryTests;

trait TestCaseTrait
{

    protected $decorators = [];
    protected $vueEnabled = false;
    protected $openVueForm = false;
    protected $csrfTokenAutoFetch = false;
    protected $generateCsrfToken = true;

    private function setUpConfig($app)
    {
        $app['config']->set('htmlfactory.decorators', $this->decorators);
        $app['config']->set('formfactory.vue.enabled', $this->vueEnabled);
        $app['config']->set('formfactory.vue.auto_csrf_refresh', $this->csrfTokenAutoFetch);
        $app['config']->set('formfactory.vue.generate_csrf_token', $this->generateCsrfToken);
    }


}

<?php

namespace FormFactoryTests\Feature\Components\FormControls\Bootstrap4Vue;

use FormFactoryTests\TestCase;

abstract class AbstractBootstrap4VueTest extends TestCase
{

    protected $decorators = ['bootstrap:v4'];
    protected $vueEnabled = true;
    protected $openVueForm = true;

}
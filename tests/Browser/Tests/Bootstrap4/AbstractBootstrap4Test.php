<?php

namespace FormFactoryTests\Browser\Tests\Bootstrap4;

use FormFactoryTests\DuskTestCase;

abstract class AbstractBootstrap4Test extends DuskTestCase
{
    protected $decorators = ['bootstrap:v4'];
}
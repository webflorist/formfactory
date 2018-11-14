<?php

namespace FormFactoryTests\Browser\Tests\Bootstrap3;

use FormFactoryTests\DuskTestCase;

abstract class AbstractBootstrap3Test extends DuskTestCase
{
    protected $decorators = ['bootstrap:v3'];

}
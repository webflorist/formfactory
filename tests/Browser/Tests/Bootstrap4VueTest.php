<?php

namespace FormFactoryTests\Browser\Tests;

use FormFactoryTests\Browser\Tests\Traits\VueTestTrait;

class Bootstrap4VueTest extends Bootstrap4Test
{
    protected $vueEnabled = true;
    protected $openVueForm = true;

    use VueTestTrait;
}
<?php

namespace FormFactoryTests\Browser\Tests;

use FormFactoryTests\Browser\Tests\Traits\VueTestTrait;

class RawVueTest extends RawTest
{
    protected $vueEnabled = true;
    protected $openVueForm = true;

    use VueTestTrait;
}
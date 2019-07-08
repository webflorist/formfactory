<?php

namespace FormFactoryTests\Browser\Tests;

use FormFactoryTests\Browser\Tests\Traits\CaptchaTestTrait;
use FormFactoryTests\Browser\Tests\Traits\HoneypotTestTrait;
use FormFactoryTests\Browser\Tests\Traits\TimeLimitTestTrait;
use FormFactoryTests\Browser\Tests\Traits\VueTestTrait;

class RawVueTest extends RawTest
{
    protected $vueEnabled = true;
    protected $openVueForm = true;

    use VueTestTrait,
        CaptchaTestTrait,
        HoneypotTestTrait,
        TimeLimitTestTrait;
}
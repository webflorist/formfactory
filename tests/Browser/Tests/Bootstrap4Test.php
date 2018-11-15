<?php

namespace FormFactoryTests\Browser\Tests;

use FormFactoryTests\Browser\Tests\Traits\CaptchaTestTrait;
use FormFactoryTests\Browser\Tests\Traits\HoneypotTestTrait;
use FormFactoryTests\Browser\Tests\Traits\TimeLimitTestTrait;
use FormFactoryTests\DuskTestCase;

class Bootstrap4Test extends DuskTestCase
{
    use CaptchaTestTrait,
        HoneypotTestTrait,
        TimeLimitTestTrait;

    protected $decorators = ['bootstrap:v4'];

}
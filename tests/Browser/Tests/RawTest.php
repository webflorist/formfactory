<?php

namespace FormFactoryTests\Browser\Tests;

use FormFactoryTests\Browser\Tests\Traits\CaptchaTestTrait;
use FormFactoryTests\Browser\Tests\Traits\HoneypotTestTrait;
use FormFactoryTests\Browser\Tests\Traits\TimeLimitTestTrait;
use FormFactoryTests\DuskTestCase;

class RawTest extends DuskTestCase
{
    use CaptchaTestTrait,
        HoneypotTestTrait,
        TimeLimitTestTrait;

}
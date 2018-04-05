<?php

namespace FormFactoryTests\Legacy\Forms;

use FormFactoryTests\Legacy\Traits\Tests\TestsValueAttribute;

class InputNumberTest extends InputTestCase
{
    use TestsValueAttribute;

    protected $tagFunction = 'number';

    protected $matchTagAttributes = ['type' => 'number'];
}
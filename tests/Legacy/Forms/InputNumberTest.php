<?php
/**
 * Created by PhpStorm.
 * User: geraldb
 * Date: 29.10.2015
 * Time: 13:24
 */

namespace FormFactoryTests\Legacy\Forms;

use FormFactoryTests\Legacy\Traits\Tests\TestsAutocompleteAttribute;
use FormFactoryTests\Legacy\Traits\Tests\TestsValueAttribute;

class InputNumberTest extends InputTestCase
{
    use TestsValueAttribute;

    protected $tagFunction = 'number';

    protected $matchTagAttributes = ['type' => 'number'];
}
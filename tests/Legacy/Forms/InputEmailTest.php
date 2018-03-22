<?php
/**
 * Created by PhpStorm.
 * User: geraldb
 * Date: 29.10.2015
 * Time: 13:24
 */

namespace FormFactoryTests\Legacy\Forms;

use FormFactoryTests\Legacy\Traits\Tests\TestsAutocompleteAttribute;
use FormFactoryTests\Legacy\Traits\Tests\TestsMaxlengthAttribute;
use FormFactoryTests\Legacy\Traits\Tests\TestsPatternAttribute;
use FormFactoryTests\Legacy\Traits\Tests\TestsPlaceholderAttribute;
use FormFactoryTests\Legacy\Traits\Tests\TestsValueAttribute;

class InputEmailTest extends InputTestCase
{
    use TestsAutocompleteAttribute, TestsPlaceholderAttribute, TestsValueAttribute, TestsPatternAttribute, TestsMaxlengthAttribute;

    protected $tagFunction = 'email';

    protected $matchTagAttributes = ['type' => 'email'];
}
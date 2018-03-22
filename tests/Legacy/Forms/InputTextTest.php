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

class InputTextTest extends InputTestCase
{
    use TestsAutocompleteAttribute, TestsPlaceholderAttribute, TestsPatternAttribute, TestsMaxlengthAttribute;

    protected $tagFunction = 'text';

    protected $matchTagAttributes = ['type' => 'text'];
}
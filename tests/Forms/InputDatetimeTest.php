<?php
/**
 * Created by PhpStorm.
 * User: geraldb
 * Date: 29.10.2015
 * Time: 13:24
 */

namespace FormBuilderTests\Forms;

use FormBuilderTests\Traits\Tests\TestsAutocompleteAttribute;
use FormBuilderTests\Traits\Tests\TestsValueAttribute;

class InputDatetimeTest extends InputTestCase
{
    use TestsAutocompleteAttribute, TestsValueAttribute;

    protected $tagFunction = 'datetime';

    protected $matchTagAttributes = ['type' => 'datetime'];
}
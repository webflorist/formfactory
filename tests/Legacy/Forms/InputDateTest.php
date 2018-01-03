<?php
/**
 * Created by PhpStorm.
 * User: geraldb
 * Date: 29.10.2015
 * Time: 13:24
 */

namespace FormBuilderTests\Legacy\Forms;

use FormBuilderTests\Legacy\Traits\Tests\TestsAutocompleteAttribute;
use FormBuilderTests\Legacy\Traits\Tests\TestsPatternAttribute;
use FormBuilderTests\Legacy\Traits\Tests\TestsValueAttribute;

class InputDateTest extends InputTestCase
{
    use TestsAutocompleteAttribute, TestsValueAttribute, TestsPatternAttribute;

    protected $tagFunction = 'date';

    protected $matchTagAttributes = ['type' => 'date'];
}
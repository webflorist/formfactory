<?php
/**
 * Created by PhpStorm.
 * User: geraldb
 * Date: 29.10.2015
 * Time: 13:24
 */

namespace FormBuilderTests\Forms;

use FormBuilderTests\Traits\Tests\TestsAutocompleteAttribute;
use FormBuilderTests\Traits\Tests\TestsPatternAttribute;
use FormBuilderTests\Traits\Tests\TestsPlaceholderAttribute;
use FormBuilderTests\Traits\Tests\TestsValueAttribute;

class InputEmailTest extends InputTestCase
{
    use TestsAutocompleteAttribute, TestsPlaceholderAttribute, TestsValueAttribute, TestsPatternAttribute;

    protected $tagFunction = 'email';

    protected $matchTagAttributes = ['type' => 'email'];
}
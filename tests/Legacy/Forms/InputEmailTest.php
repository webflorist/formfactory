<?php
/**
 * Created by PhpStorm.
 * User: geraldb
 * Date: 29.10.2015
 * Time: 13:24
 */

namespace FormBuilderTests\Legacy\Forms;

use FormBuilderTests\Legacy\Traits\Tests\TestsAutocompleteAttribute;
use FormBuilderTests\Legacy\Traits\Tests\TestsMaxlengthAttribute;
use FormBuilderTests\Legacy\Traits\Tests\TestsPatternAttribute;
use FormBuilderTests\Legacy\Traits\Tests\TestsPlaceholderAttribute;
use FormBuilderTests\Legacy\Traits\Tests\TestsValueAttribute;

class InputEmailTest extends InputTestCase
{
    use TestsAutocompleteAttribute, TestsPlaceholderAttribute, TestsValueAttribute, TestsPatternAttribute, TestsMaxlengthAttribute;

    protected $tagFunction = 'email';

    protected $matchTagAttributes = ['type' => 'email'];
}
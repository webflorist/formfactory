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

class InputTextTest extends InputTestCase
{
    use TestsAutocompleteAttribute, TestsPlaceholderAttribute, TestsPatternAttribute, TestsMaxlengthAttribute;

    protected $tagFunction = 'text';

    protected $matchTagAttributes = ['type' => 'text'];
}
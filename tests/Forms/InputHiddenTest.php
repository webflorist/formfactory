<?php
/**
 * Created by PhpStorm.
 * User: geraldb
 * Date: 29.10.2015
 * Time: 13:24
 */

namespace FormBuilderTests\Forms;

use FormBuilderTests\Traits\Tests\TestsValueAttribute;

class InputHiddenTest extends InputTestCase
{
    use TestsValueAttribute;

    protected $wrapperMatcher = [];

    protected $helpTextMatcher = [];

    protected $tagFunction = 'hidden';

    protected $matchTagAttributes = ['type' => 'hidden'];
}
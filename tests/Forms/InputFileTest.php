<?php
/**
 * Created by PhpStorm.
 * User: geraldb
 * Date: 29.10.2015
 * Time: 13:24
 */

namespace FormBuilderTests\Forms;

use FormBuilderTests\Traits\Tests\TestsAcceptAttribute;
use FormBuilderTests\Traits\Tests\TestsValueAttribute;

class InputFileTest extends InputTestCase
{
    use TestsAcceptAttribute, TestsValueAttribute;

    protected $tagFunction = 'file';

    protected $matchTagAttributes = ['type' => 'file', 'class' => 'form-control-file'];
}
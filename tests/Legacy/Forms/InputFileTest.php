<?php
/**
 * Created by PhpStorm.
 * User: geraldb
 * Date: 29.10.2015
 * Time: 13:24
 */

namespace FormFactoryTests\Legacy\Forms;

use FormFactoryTests\Legacy\Traits\Tests\TestsAcceptAttribute;
use FormFactoryTests\Legacy\Traits\Tests\TestsValueAttribute;

class InputFileTest extends InputTestCase
{
    use TestsAcceptAttribute, TestsValueAttribute;

    protected $tagFunction = 'file';

    protected $matchTagAttributes = ['type' => 'file', 'class' => 'form-control-file'];
}
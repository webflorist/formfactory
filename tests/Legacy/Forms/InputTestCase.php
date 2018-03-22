<?php
/**
 * Created by PhpStorm.
 * User: GeraldB
 * Date: 06.11.2015
 * Time: 13:41
 */

namespace FormFactoryTests\Legacy\Forms;

use FormFactoryTests\Legacy\Traits\Tests\FieldTests;
use FormFactoryTests\Legacy\Traits\Tests\TestsAutofocusAttribute;
use FormFactoryTests\Legacy\Traits\Tests\TestsDisabledAttribute;
use FormFactoryTests\Legacy\Traits\Tests\TestsReadonlyAttribute;

class InputTestCase extends FieldTestCase
{
    use FieldTests,
        TestsReadonlyAttribute,
        TestsAutofocusAttribute,
        TestsDisabledAttribute;

    // TODO: TestsMaxAttribute, TestsMinAttribute;

    protected $tag = 'input';


}
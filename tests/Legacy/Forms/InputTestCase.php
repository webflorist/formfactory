<?php
/**
 * Created by PhpStorm.
 * User: GeraldB
 * Date: 06.11.2015
 * Time: 13:41
 */

namespace FormBuilderTests\Legacy\Forms;

use FormBuilderTests\Legacy\Traits\Tests\FieldTests;
use FormBuilderTests\Legacy\Traits\Tests\TestsAutofocusAttribute;
use FormBuilderTests\Legacy\Traits\Tests\TestsDisabledAttribute;
use FormBuilderTests\Legacy\Traits\Tests\TestsReadonlyAttribute;

class InputTestCase extends FieldTestCase
{
    use FieldTests,
        TestsReadonlyAttribute,
        TestsAutofocusAttribute,
        TestsDisabledAttribute;

    // TODO: TestsMaxAttribute, TestsMinAttribute;

    protected $tag = 'input';


}
<?php
/**
 * Created by PhpStorm.
 * User: GeraldB
 * Date: 06.11.2015
 * Time: 13:41
 */

namespace FormBuilderTests\Forms;

use FormBuilderTests\Traits\Tests\FieldTests;
use FormBuilderTests\Traits\Tests\TestsAutofocusAttribute;
use FormBuilderTests\Traits\Tests\TestsDisabledAttribute;
use FormBuilderTests\Traits\Tests\TestsReadonlyAttribute;

class InputTestCase extends FieldTestCase
{
    use FieldTests,
        TestsReadonlyAttribute,
        TestsAutofocusAttribute,
        TestsDisabledAttribute;

    // TODO: TestsMaxAttribute, TestsMinAttribute;

    protected $tag = 'input';


}
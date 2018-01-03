<?php
/**
 * Created by PhpStorm.
 * User: geraldb
 * Date: 29.10.2015
 * Time: 13:24
 */

namespace FormBuilderTests\Legacy\Forms;

use FormBuilderTests\Legacy\Traits\Tests\FieldTests;
use FormBuilderTests\Legacy\Traits\Tests\TestsAutofocusAttribute;
use FormBuilderTests\Legacy\Traits\Tests\TestsDisabledAttribute;
use FormBuilderTests\Legacy\Traits\Tests\TestsMaxlengthAttribute;
use FormBuilderTests\Legacy\Traits\Tests\TestsPlaceholderAttribute;
use FormBuilderTests\Legacy\Traits\Tests\TestsReadonlyAttribute;
use FormBuilderTests\Legacy\Traits\Tests\TestsTagContent;

class TextareaTest extends FieldTestCase
{

    use FieldTests,
        TestsPlaceholderAttribute,
        TestsReadonlyAttribute,
        TestsMaxlengthAttribute,
        TestsDisabledAttribute,
        TestsAutofocusAttribute,
        TestsTagContent;

    protected $tag = 'textarea';

    protected $tagFunction = 'textarea';

    /** Tests:
    Form::open('myFormName')->values('myFieldName' => 'myNewValue');
    %tagCall%;
     */
    public function testTextareaTag_setValueViaFormOpen() {

        $this->formTemplate['methods'] = [
            [
                'name' => 'values',
                'parameters' => [
                    [
                        'myFieldName' => 'myNewValue'
                    ]
                ]
            ]
        ];

        $this->performTagTest();
    }
}
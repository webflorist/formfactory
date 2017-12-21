<?php
/**
 * Created by PhpStorm.
 * User: geraldb
 * Date: 29.10.2015
 * Time: 13:24
 */

namespace FormBuilderTests\Forms;

use FormBuilderTests\Traits\Tests\FieldTests;
use FormBuilderTests\Traits\Tests\TestsAutofocusAttribute;
use FormBuilderTests\Traits\Tests\TestsDisabledAttribute;
use FormBuilderTests\Traits\Tests\TestsMaxlengthAttribute;
use FormBuilderTests\Traits\Tests\TestsPlaceholderAttribute;
use FormBuilderTests\Traits\Tests\TestsReadonlyAttribute;
use FormBuilderTests\Traits\Tests\TestsTagContent;

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
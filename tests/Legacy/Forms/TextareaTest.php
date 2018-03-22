<?php
/**
 * Created by PhpStorm.
 * User: geraldb
 * Date: 29.10.2015
 * Time: 13:24
 */

namespace FormFactoryTests\Legacy\Forms;

use FormFactoryTests\Legacy\Traits\Tests\FieldTests;
use FormFactoryTests\Legacy\Traits\Tests\TestsAutofocusAttribute;
use FormFactoryTests\Legacy\Traits\Tests\TestsDisabledAttribute;
use FormFactoryTests\Legacy\Traits\Tests\TestsMaxlengthAttribute;
use FormFactoryTests\Legacy\Traits\Tests\TestsPlaceholderAttribute;
use FormFactoryTests\Legacy\Traits\Tests\TestsReadonlyAttribute;
use FormFactoryTests\Legacy\Traits\Tests\TestsTagContent;

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
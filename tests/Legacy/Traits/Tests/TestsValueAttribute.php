<?php
/**
 * Created by PhpStorm.
 * User: GeraldB
 * Date: 06.11.2015
 * Time: 14:10
 */

namespace FormFactoryTests\Legacy\Traits\Tests;


trait TestsValueAttribute
{
    /** Tests:
    Form::open('myFormName');
    %tagCall%->value('myNewValue');
     */
    public function testFieldTag_setValue() {

        $this->tagMethods = [
            [
                'name' => 'value',
                'parameters' => [
                    'myNewValue'
                ]
            ]
        ];

        $this->performTagTest();
    }

    /** Tests:
    Form::open('myFormName')->values('myFieldName' => 'myNewValue');
    %tagCall%;
     */
    public function testFieldTag_setValueViaFormOpen() {

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
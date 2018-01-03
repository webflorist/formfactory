<?php
/**
 * Created by PhpStorm.
 * User: GeraldB
 * Date: 06.11.2015
 * Time: 14:10
 */

namespace FormBuilderTests\Legacy\Traits\Tests;


trait TestsCheckedAttribute
{
    /** Tests:
    Form::open('myFormName');
    %tagCall%->checked()
     */
    public function testFieldTag_setChecked() {

        $this->tagMethods = [
            [
                'name' => 'checked',
                'parameters' => []
            ]
        ];

        $this->performTagTest();
    }

    /** Tests:
    Form::open('myFormName')->values('myFieldName' => 'myFieldValue');
    %tagCall%;
     */
    public function testFieldTag_setCheckedViaFormOpen() {

        $this->formTemplate['methods'] = [
            [
                'name' => 'values',
                'parameters' => [
                    [
                        'myFieldName' => 'myFieldValue'
                    ]
                ]
            ]
        ];

        $this->performTagTest();
    }
}
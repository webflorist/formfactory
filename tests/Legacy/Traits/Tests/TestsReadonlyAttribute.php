<?php
/**
 * Created by PhpStorm.
 * User: GeraldB
 * Date: 06.11.2015
 * Time: 14:08
 */

namespace FormBuilderTests\Legacy\Traits\Tests;


trait TestsReadonlyAttribute
{
    /** Tests:
    Form::open('myFormName');
    %tagCall%->readonly()
     */
    public function testFieldTag_setReadonly() {

        $this->tagMethods = [
            [
                'name' => 'readonly',
                'parameters' => []
            ]
        ];

        $this->performTagTest();
    }
}
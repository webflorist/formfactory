<?php
/**
 * Created by PhpStorm.
 * User: GeraldB
 * Date: 06.11.2015
 * Time: 14:07
 */

namespace FormFactoryTests\Legacy\Traits\Tests;


trait TestsMaxlengthAttribute
{
    /** Tests:
    Form::open('myFormName');
    %tagCall%->maxlength(10)
     */
    public function testFieldTag_setMaxlength() {

        $this->tagMethods = [
            [
                'name' => 'maxlength',
                'parameters' => [10]
            ]
        ];

        $this->performTagTest();
    }
}
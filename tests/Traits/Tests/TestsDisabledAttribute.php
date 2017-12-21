<?php
/**
 * Created by PhpStorm.
 * User: GeraldB
 * Date: 06.11.2015
 * Time: 14:10
 */

namespace FormBuilderTests\Traits\Tests;


trait TestsDisabledAttribute
{
    /** Tests:
    Form::open('myFormName');
    %tagCall%->disabled()
     */
    public function testFieldTag_setDisabled() {

        $this->tagMethods = [
            [
                'name' => 'disabled',
                'parameters' => []
            ]
        ];

        $this->performTagTest();
    }
}
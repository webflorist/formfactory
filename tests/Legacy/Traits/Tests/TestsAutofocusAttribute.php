<?php
/**
 * Created by PhpStorm.
 * User: GeraldB
 * Date: 06.11.2015
 * Time: 14:08
 */

namespace FormFactoryTests\Legacy\Traits\Tests;


trait TestsAutofocusAttribute
{
    /** Tests:
    Form::open('myFormName');
    %tagCall%->autofocus()
     */
    public function testFieldTag_setAutofocus()
    {

        $this->tagMethods = [
            [
                'name' => 'autofocus',
                'parameters' => []
            ]
        ];

        $this->performTagTest();
    }
}
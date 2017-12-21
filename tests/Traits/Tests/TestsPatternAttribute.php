<?php
/**
 * Created by PhpStorm.
 * User: GeraldB
 * Date: 06.11.2015
 * Time: 14:05
 */

namespace FormBuilderTests\Traits\Tests;


trait TestsPatternAttribute
{

    /** Tests:
    Form::open('myFormName');
    %call%->pattern('[A-Za-z]{3}')
     */
    public function testFieldTag_setPattern() {

        $this->tagMethods = [
            [
                'name' => 'pattern',
                'parameters' => ['[A-Za-z]{3}']
            ]
        ];

        $this->performTagTest();
    }
}
<?php

/**
 * Created by PhpStorm.
 * User: GeraldB
 * Date: 06.11.2015
 * Time: 12:01
 */

namespace FormFactoryTests\Legacy\Traits\Tests;

trait TestsContext
{

    /** Tests:
    %tagCall%->context()
     */
    public function testFieldTag_setContext() {

        $this->tagMethods = [
            [
                'name' => 'context',
                'parameters' => [
                    'MyNewContext'
                ]
            ]
        ];

        $this->performTagTest();
    }

}
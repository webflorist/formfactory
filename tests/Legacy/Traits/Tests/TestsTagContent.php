<?php

/**
 * Created by PhpStorm.
 * User: GeraldB
 * Date: 06.11.2015
 * Time: 12:01
 */

namespace FormFactoryTests\Legacy\Traits\Tests;

trait TestsTagContent
{

    /** Tests:
    %tagCall%->content()
     */
    public function testFieldTag_setContent() {

        $this->tagMethods = [
            [
                'name' => 'content',
                'parameters' => [
                    'MyNewContent'
                ]
            ]
        ];

        $this->performTagTest();
    }

}
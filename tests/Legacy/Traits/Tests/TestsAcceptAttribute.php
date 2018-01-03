<?php
/**
 * Created by PhpStorm.
 * User: GeraldB
 * Date: 06.11.2015
 * Time: 14:09
 */

namespace FormBuilderTests\Legacy\Traits\Tests;


trait TestsAcceptAttribute
{
    /** Tests:
    Form::open('myFormName');
    %tagCall%->accept()
     */
    public function testFieldTag_setAccept() {

        $this->tagMethods = [
            [
                'name' => 'accept',
                'parameters' => [
                    'file_extension|audio/*|video/*|image/*|media_type'
                ]
            ]
        ];

        $this->performTagTest();
    }
}
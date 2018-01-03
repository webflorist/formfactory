<?php

/**
 * Created by PhpStorm.
 * User: GeraldB
 * Date: 06.11.2015
 * Time: 12:01
 */

namespace FormBuilderTests\Legacy\Traits\Tests;

trait TagTests
{

    /** Tests:
    %tagCall%->hidden()
     */
    public function testFieldTag_setHidden() {

        $this->tagMethods = [
            [
                'name' => 'hidden',
                'parameters' => []
            ]
        ];

        $this->performTagTest();
    }

    /** Tests:
    %tagCall%->title('MyTitle')
     */
    public function testFieldTag_setTitle() {

        $this->tagMethods = [
            [
                'name' => 'title',
                'parameters' => ['MyTitle']
            ]
        ];

        $this->performTagTest();
    }

    /** Tests:
    %tagCall%->data('mydataattribute','mydatavalue')
     */
    public function testTag_setData() {

        $this->tagMethods = [
            [
                'name' => 'data',
                'parameters' => [
                    'mydataattribute',
                    'mydatavalue'
                ]
            ]
        ];

        $this->performTagTest();
    }

    /** Tests:
    %tagCall%->addClass('myAdditionalClass');
     */
    public function testTag_addClass() {

        $this->tagMethods = [
            [
                'name' => 'addClass',
                'parameters' => [
                    'myAdditionalClass'
                ]
            ]
        ];

        $this->performTagTest();
    }

    /** Tests:
    %tagCall%->id('myNewId');
     */
    public function testTag_setId() {

        $this->tagMethods = [
            [
                'name' => 'id',
                'parameters' => [
                    'myNewId'
                ]
            ]
        ];

        $this->performTagTest();
    }

    /** Tests:
    %tagCall%;
     */
    public function testTag_simple() {

        $this->performTagTest();

    }

}
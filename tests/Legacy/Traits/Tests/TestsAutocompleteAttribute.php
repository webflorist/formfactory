<?php
/**
 * Created by PhpStorm.
 * User: GeraldB
 * Date: 06.11.2015
 * Time: 14:09
 */

namespace FormBuilderTests\Legacy\Traits\Tests;


trait TestsAutocompleteAttribute
{
    /** Tests:
    Form::open('myFormName');
    %tagCall%->autocomplete()
     */
    public function testFieldTag_setAutocomplete() {

        $this->tagMethods = [
            [
                'name' => 'autocomplete',
                'parameters' => []
            ]
        ];

        $this->performTagTest();
    }
}
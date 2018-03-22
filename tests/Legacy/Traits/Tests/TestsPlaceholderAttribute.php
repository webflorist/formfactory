<?php
/**
 * Created by PhpStorm.
 * User: GeraldB
 * Date: 06.11.2015
 * Time: 14:11
 */

namespace FormFactoryTests\Legacy\Traits\Tests;


trait TestsPlaceholderAttribute
{

    protected function matcherModifier_Placeholder() {
        // Default-placeholder.
        $this->matchTagAttributes['placeholder'] = ucfirst($this->tagParameters['name']);
    }

    /** Tests:
    Form::open('myFormName');
    %tagCall%->placeholder('myNewPlaceholder');
     */
    public function testFieldTag_setPlaceholder() {

        $this->tagMethods = [
            [
                'name' => 'placeholder',
                'parameters' => [
                    'myNewPlaceholder'
                ]
            ]
        ];

        $this->performTagTest();
    }
}
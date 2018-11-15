<?php
/**
 * Created by PhpStorm.
 * User: geraldb
 * Date: 29.10.2015
 * Time: 13:24
 */

namespace FormFactoryTests\Legacy\Forms;

use FormFactoryTests\Legacy\Traits\Tests\FieldTests;
use FormFactoryTests\Legacy\Traits\Tests\TestsDisabledAttribute;

class SelectTest extends FieldTestCase
{

    use FieldTests,
        TestsDisabledAttribute;

    protected $tag = 'select';

    protected $tagFunction = 'select';

    protected function generateTag() {

        $this->tagParameters = [
            'name' => 'myFieldName',
            'options' => [
                $this->callFormFactoryFunction('option',['myFirstOption']),
                $this->callFormFactoryFunction('option',['mySecondOption']),
            ]
        ];

        parent::generateTag();

    }

    protected function matcherModifier_selectOptions()
    {

        $formID = $this->formTemplate['parameters']['id'];
        $fieldName = $this->tagParameters['name'];

        $this->matchTagChildren = [
            [
                'tag' => 'option',
                'attributes' => [
                    'value' => 'myFirstOption',
                ],
                'children' => [
                    [
                        'text' => 'myFirstOption'
                    ]
                ]
            ],
            [
                'tag' => 'option',
                'attributes' => [
                    'value' => 'mySecondOption',
                ],
                'children' => [
                    [
                        'text' => 'mySecondOption'
                    ]
                ]
            ]
        ];
    }

}
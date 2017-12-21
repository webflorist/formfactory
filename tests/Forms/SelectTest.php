<?php
/**
 * Created by PhpStorm.
 * User: geraldb
 * Date: 29.10.2015
 * Time: 13:24
 */

namespace FormBuilderTests\Forms;

use FormBuilderTests\Traits\Tests\FieldTests;
use FormBuilderTests\Traits\Tests\TestsDisabledAttribute;

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
                $this->callFormBuilderFunction('option',['myFirstOption']),
                $this->callFormBuilderFunction('option',['mySecondOption']),
            ]
        ];

        parent::generateTag();

    }

    protected function matcherModifier_selectOptions()
    {

        $formID = $this->formTemplate['parameters']['id'];
        $fieldName = $this->tagParameters['name'];

        $optionBaseID = $formID . '_' . $fieldName . '_';

        $this->matchTagChildren = [
            [
                'tag' => 'option',
                'attributes' => [
                    'id' => $optionBaseID . 'myFirstOption',
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
                    'id' => $optionBaseID . 'mySecondOption',
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
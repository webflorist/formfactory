<?php
/**
 * Created by PhpStorm.
 * User: geraldb
 * Date: 29.10.2015
 * Time: 13:24
 */

namespace FormBuilderTests\Forms;

use FormBuilderTests\TestCase;
use FormBuilderTests\Traits\Tests\TestsAutofocusAttribute;
use FormBuilderTests\Traits\Tests\TestsContext;
use FormBuilderTests\Traits\Tests\TestsDisabledAttribute;
use FormBuilderTests\Traits\Tests\TestsTagContent;
use FormBuilderTests\Traits\Tests\TestsValueAttribute;

class ButtonTest extends TestCase
{
    use TestsTagContent,
        TestsValueAttribute,
        TestsAutofocusAttribute,
        TestsDisabledAttribute,
        TestsContext;

    protected $tag = 'button';

    protected $tagFunction = 'button';

    protected $tagParameters = [
        'name' => 'myButtonName'
    ];

    protected $formTemplate = [
        'parameters' => [
            'id' => 'myFormId',
        ],
        'methods' => [
        ]
    ];

    protected $context = 'default';

    protected $buttonType = 'button';

    protected function generateTag() {

        // Open the Form using $this->formTemplate.
        $this->callFormBuilderFunction('open',[$this->formTemplate['parameters']['id']],$this->formTemplate['methods']);

        parent::generateTag();

    }

    protected function applyTagMethods2MatcherData() {

        $formID = $this->formTemplate['parameters']['id'];
        $buttonName = $this->tagParameters['name'];

        $buttonID = $formID . '_' . $buttonName;

        $this->matchTagAttributes = [
            'type' => $this->buttonType,
            'class' => 'btn',
            'id' => $buttonID,
            'name' => $buttonName
        ];

        $this->matchTagChildren = [
            [
                'text' => ucfirst($buttonName),
            ]
        ];

        parent::applyTagMethods2MatcherData();

        $this->addHtmlClass2String($this->matchTagAttributes['class'], 'btn-'.$this->context);

    }

}
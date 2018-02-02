<?php
/**
 * Created by PhpStorm.
 * User: geraldb
 * Date: 29.10.2015
 * Time: 13:24
 */

namespace FormBuilderTests\Legacy\Forms;

use FormBuilderTests\Legacy\TestCase;

class FieldTestCase extends TestCase
{

    protected $wrapperMatcher = [
        'tag' => 'div',
        'attributes' => [
            'class' => 'form-group',
            'data-field-wrapper' => '1'
        ]
    ];

    protected $labelMatcher = [];

    protected $labelMode = 'bound'; // bound|before|after|sr-only|none

    protected $helpTextMatcher = [];

    protected $errorMatcher = [];

    protected $tagParameters = [
        'name' => 'myFieldName'
    ];

    protected $formTemplate = [
        'parameters' => [
            'id' => 'myFormId',
        ],
        'methods' => [
        ]
    ];

    protected function generateTag() {

        // Open the Form using $this->formTemplate.
        $this->callFormBuilderFunction('open',[$this->formTemplate['parameters']['id']],$this->formTemplate['methods']);

        parent::generateTag();

    }

    protected function setUpMatcherData() {

        parent::setUpMatcherData();

    }

    protected function applyTagMethods2MatcherData() {

        parent::applyTagMethods2MatcherData();

        foreach ($this->tagParameters as $optionType => $optionValue) {
            switch ($optionType) {
                // Change wrapping accordingly.
                case 'wrap':
                    if ($optionValue === false) {
                        unset($this->wrapperMatcher);
                    }
                    // TODO: else if other wrapper -> modify $this->wrapperMatcher accordingly.
                    break;
                case 'options':
                    break;
                // Per default we assume the methodName is a HTML-attribute.
                default:
                    $this->matchTagAttributes[$optionType] = $optionValue;
            }
        }

        // Apply changes required by methods set on the Form::open call
        if (isset($this->formTemplate['methods']) && (count($this->formTemplate['methods'])>0)) {
            foreach ($this->formTemplate['methods'] as $key => $methodData) {
                switch ($methodData['name']) {
                    case 'errors':
                        if (isset($methodData['parameters'][0][$this->matchTagAttributes['name']])) {
                            $this->tagMethod2Matcher_errors($methodData['parameters'][0][$this->matchTagAttributes['name']]);
                        }
                        break;
                    case 'rules':
                        if (isset($methodData['parameters'][0][$this->matchTagAttributes['name']])) {
                            $this->tagMethod2Matcher_rules($methodData['parameters'][0][$this->matchTagAttributes['name']]);
                        }
                        break;
                    case 'values':
                        if (isset($methodData['parameters'][0][$this->matchTagAttributes['name']])) {
                            if ($this->tag === 'input') {
                                if (($this->matchTagAttributes['type'] === 'checkbox') || ($this->matchTagAttributes['type'] === 'radio')) {
                                    if ($this->matchTagAttributes['value'] === $methodData['parameters'][0][$this->matchTagAttributes['name']]) {
                                        $this->tagMethod2Matcher_checked(true);
                                    }
                                }
                                else {
                                    $this->matchTagAttributes['value'] = $methodData['parameters'][0][$this->matchTagAttributes['name']];
                                }
                            }
                            else if ($this->tag === 'textarea') {
                                $this->tagMethod2Matcher_content($methodData['parameters'][0][$this->matchTagAttributes['name']]);
                            }
                        }
                        break;
                }
            }
        }

    }

    protected function generateMatcher() {


        parent::generateMatcher();

        if (($this->labelMode === 'after') || ($this->labelMode === 'before')) {

            $this->labelMatcher['children'][0]['text'] = $this->labelMatcher['children'][0]['text'];

            if ($this->labelMode === 'after') {
                array_unshift($this->labelMatcher['children'],$this->matcher[0]);
            }
            else {
                array_push($this->labelMatcher['children'],$this->matcher[0]);
            }
            $this->matcher[0] = [
                'tag' => 'label',
                'children' => $this->labelMatcher['children']
            ];
        }

        if (isset($this->errorMatcher) && (count($this->errorMatcher)>0)) {
            array_unshift($this->matcher,$this->errorMatcher);
        }

        if (($this->labelMode === 'bound') && isset($this->labelMatcher) && (count($this->labelMatcher)>0)) {
            array_unshift($this->matcher,$this->labelMatcher);
        }

        if (isset($this->helpTextMatcher) && (count($this->helpTextMatcher)>0)) {
            array_push($this->matcher,$this->helpTextMatcher);
        }

        if (isset($this->wrapperMatcher) && (count($this->wrapperMatcher)>0)) {
            $this->wrapperMatcher['children'] = $this->matcher;
            $this->matcher = [$this->wrapperMatcher];
        }

    }


}
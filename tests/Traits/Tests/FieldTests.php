<?php
/**
 * Created by PhpStorm.
 * User: GeraldB
 * Date: 06.11.2015
 * Time: 13:52
 */

namespace FormBuilderTests\Traits\Tests;

trait FieldTests
{

    protected function matcherModifier_generalFields() {

        $formID = $this->formTemplate['parameters']['id'];
        $fieldName = $this->tagParameters['name'];

        $fieldID = $formID.'_'.$fieldName;

        if (isset($this->matchTagAttributes['type']) && ($this->matchTagAttributes['type'] === 'radio')) {
            $fieldID = $formID.'_'.$fieldName.'_'.$this->tagParameters['value'];
        }

        if (!(isset($this->matchTagAttributes['type']) && ($this->matchTagAttributes['type'] === 'hidden')) || $this->tagMethodExists('labelMode',['sr-only'])){
            // The standard label-template for fields.
            $labelText = ucfirst($fieldName);
            if (isset($this->matchTagAttributes['type']) && ($this->matchTagAttributes['type'] === 'radio')) {
                $labelText = ucfirst($this->tagParameters['value']);
            }
            $this->labelMatcher = [
                'tag' => 'label',
                'attributes' => [
                    'for' => $fieldID,
                ],
                'children' => [
                    [
                        'text' => $labelText
                    ]
                ]
            ];
        }

        $this->matchTagAttributes['id'] = $fieldID;
        $this->matchTagAttributes['name'] = $fieldID;

        /*
        if ($this->tag === 'input') {
            $this->matchTagAttributes['value'] = '';
        }
        */

        if (!isset($this->matchTagAttributes['class'])) {
            $this->matchTagAttributes['class'] = 'form-control';
        }

    }

    /** Tests:
    Form::open('myFormName');
    %tagCall%->autoSubmit()
     */
    public function testFieldTag_setAutoSubmit() {

        $this->tagMethods = [
            [
                'name' => 'autoSubmit',
                'parameters' => []
            ]
        ];

        $this->performTagTest();
    }

    /** Tests:
    Form::open('myFormName');
    %tagCall%->ajaxValidation('onChange')
     */
    public function testFieldTag_setAjaxValidationOnChange() {

        $this->tagMethods = [
            [
                'name' => 'ajaxValidation',
                'parameters' => ['onChange']
            ]
        ];

        $this->performTagTest();
    }

    /** Tests:
    Form::open('myFormName');
    %tagCall%->labelMode('none')
     */
    public function testFieldTag_setLabelModeNone() {

        if (($this->labelMode !== 'after') && ($this->labelMode !== 'before')) {
            $this->tagMethods = [
                [
                    'name' => 'labelMode',
                    'parameters' => ['none']
                ]
            ];

            $this->performTagTest();
        }

    }

    /** Tests:
    Form::open('myFormName');
    %tagCall%->labelMode('sr-only')
     */
    public function testFieldTag_setLabelModeSrOnly() {

        if (($this->labelMode !== 'after') && ($this->labelMode !== 'before')) {
            $this->tagMethods = [
                [
                    'name' => 'labelMode',
                    'parameters' => ['sr-only']
                ]
            ];

            $this->performTagTest();
        }

    }

    /** Tests:
    Form::open('myFormName');
    %tagCall%->label('MyLabel')
     */
    public function testFieldTag_setLabel() {

        $this->tagMethods = [
            [
                'name' => 'label',
                'parameters' => [
                    'MyLabel'
                ]
            ]
        ];

        $this->performTagTest();
    }

    /** Tests:
    Form::open('myFormName')->errors('myFieldName' => 'required|alpha|max:10');
    %tagCall%;
     */
    public function testFieldTag_setRulesViaFormOpen() {

        $this->formTemplate['methods'] = [
            [
                'name' => 'rules',
                'parameters' => [
                    [
                        'myFieldName' => 'required|alpha|max:10'
                    ]
                ]
            ]
        ];

        $this->performTagTest();

    }

    /** Tests:
    Form::open('myFormName');
    %tagCall%->rules('required|alpha|max:10')
     */
    public function testFieldTag_setRules() {

        $this->tagMethods = [
            [
                'name' => 'rules',
                'parameters' => ['required|alpha|max:10']
            ]
        ];

        $this->performTagTest();
    }

    /** Tests:
    Form::open('myFormName')->errors('myFieldName' => ['myFirstError','mySecondError']);
    %tagCall%;
     */
    public function testFieldTag_setErrorsViaFormOpen() {

        $this->formTemplate['methods'] = [
            [
                'name' => 'errors',
                'parameters' => [
                    [
                        'myFieldName' => ['myFirstError','mySecondError']
                    ]
                ]
            ]
        ];

        $this->performTagTest();

    }

    /** Tests:
    Form::open('myFormName');
    %tagCall%->errors(['myFirstError','mySecondError'])
     */
    public function testFieldTag_setErrors() {

        $this->tagMethods = [
            [
                'name' => 'errors',
                'parameters' => [['myFirstError','mySecondError']]
            ]
        ];

        $this->performTagTest();
    }

    /** Tests:
    Form::open('myFormName');
    %tagCall%->helpText('myHelpText')
     */
    public function testFieldTag_setHelpText() {

        $this->tagMethods = [
            [
                'name' => 'helpText',
                'parameters' => ['myHelpText']
            ]
        ];

        $this->performTagTest();
    }

    /** Tests:
    Form::open('myFormName');
    %tagCall%->required()
     */
    public function testFieldTag_setRequired() {

        $this->tagMethods = [
            [
                'name' => 'required',
                'parameters' => []
            ]
        ];

        $this->performTagTest();
    }

}
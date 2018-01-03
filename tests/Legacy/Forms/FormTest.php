<?php
/**
 * Created by PhpStorm.
 * User: geraldb
 * Date: 29.10.2015
 * Time: 13:24
 */

namespace FormBuilderTests\Legacy\Forms;

use FormBuilderTests\Legacy\Forms\Requests\CaptchaTestRequest;
use FormBuilderTests\Legacy\Forms\Requests\DefaultTestRequest;
use FormBuilderTests\Legacy\Forms\Requests\HoneypotTestRequest;
use FormBuilderTests\Legacy\TestCase;

class FormTest extends TestCase
{
    protected $tag = 'form';

    protected $tagFunction = 'open';

    protected $tagParameters = [
        'id' => 'myFormID',
    ];

    protected $matchTagAttributes = [
        'id' => 'myFormID',
        'action' => 'http://192.168.56.103:8000',
        'method' => 'POST',
        'role' => 'form',
        'accept-charset' => 'UTF-8',
        'class' => 'form-vertical',
        'enctype' => 'multipart/form-data'
    ];

    protected $requestObject = null;

    protected $honeypotFieldName = null;

    protected $hasCaptcha = null;

    protected function generateTag() {

        parent::generateTag();

        $this->html .= '</form>';

    }

    protected function tagMethod2Matcher_requestObject($requestObject='') {
        $this->requestObject = $requestObject;
    }

    protected function applyTagMethods2MatcherData() {

        parent::applyTagMethods2MatcherData();

        // Add hidden-input field for _token.
        if ($this->matchTagAttributes['method'] !== 'GET') {
            $this->matchTagChildren[] = [
                'tag' => 'input',
                'attributes' => [
                    'type' => 'hidden',
                    'id' => $this->matchTagAttributes['id'].'__token',
                    'class' => 'form-control',
                    'name' => '_token',
                    'value' => ''
                ]
            ];
        }

        // Add hidden-input field for _formID.
        $this->matchTagChildren[] = [
            'tag' => 'input',
            'attributes' => [
                'type' => 'hidden',
                'id' => $this->matchTagAttributes['id'].'__formID',
                'class' => 'form-control',
                'name' => '_formID',
                'value' => $this->matchTagAttributes['id']
            ]
        ];

        if (($this->matchTagAttributes['method'] !== 'GET') && ($this->matchTagAttributes['method'] !== 'POST')) {
            $this->matchTagChildren[] = [
                'tag' => 'input',
                'attributes' => [
                    'type' => 'hidden',
                    'id' => $this->matchTagAttributes['id'].'__method',
                    'class' => 'form-control',
                    'name' => '_method',
                    'value' => $this->matchTagAttributes['method']
                ]
            ];
            $this->matchTagAttributes['method'] = 'POST';
        }

        // Add hidden-input honeypot field, if one was set.
        if (strlen($this->honeypotFieldName)>0) {
            $this->matchTagChildren[] = [
                'tag' => 'div',
                'attributes' => [
                    'hidden' => true,
                    'class' => 'form-group'
                ],
                'children' => [
                    [
                        'tag' => 'label',
                        'attributes' => [
                            'for' => $this->matchTagAttributes['id'].'_'.$this->honeypotFieldName
                        ],
                        'children' => [
                            [
                                'text' => 'Please leave this field blank.'
                            ]
                        ]
                    ],
                    [
                        'tag' => 'input',
                        'attributes' => [
                            'type' => 'text',
                            'id' => $this->matchTagAttributes['id'].'_'.$this->honeypotFieldName,
                            'class' => 'form-control',
                            'name' => $this->honeypotFieldName,
                            'value' => "",
                            'placeholder' => ucwords($this->honeypotFieldName)
                        ]
                    ]
                ]
            ];
        }

        // Add captcha field, if one was set.
        if ($this->hasCaptcha === true) {

            $this->matchTagChildren[] = [
                'tag' => 'div',
                'attributes' => [
                    'class' => 'form-group'
                ],
                'children' => [
                    [
                        'tag' => 'label',
                        'attributes' => [
                            'for' => $this->matchTagAttributes['id'].'__captcha'
                        ],
                        'children' => [
                            [
                                'text' => \Session::get('formbuilder.captcha.' . $this->requestObject . '.question')
                            ],
                            [
                                'tag' => 'sup',
                                'children' => [
                                    [
                                        'text' => '*'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'tag' => 'input',
                        'attributes' => [
                            'type' => 'text',
                            'id' => $this->matchTagAttributes['id'].'__captcha',
                            'aria-describedby' => $this->matchTagAttributes['id'].'__captcha_helpText',
                            'class' => 'form-control',
                            'required' => true,
                            'name' => '_captcha',
                            'value' => "",
                            'placeholder' => 'Please solve the above mentioned calculation.'
                        ]
                    ],
                    [
                        'tag' => 'div',
                        'attributes' => [
                            'class' => 'text-muted small',
                            'id' => $this->matchTagAttributes['id'].'__captcha_helpText',
                        ],
                        'children' => [
                            [
                                'text' => 'This task is necessary to protect the form against automated attacks.'
                            ]
                        ]
                    ]
                ]
            ];
        }

    }

    /** Tests:
    %tagCall%->action('MyAction')
     */
    public function testFormTag_setAction() {

        $this->tagMethods = [
            [
                'name' => 'action',
                'parameters' => ['MyAction']
            ]
        ];

        $this->performTagTest();
    }

    /** Tests:
    %tagCall%->method('get')
     */
    public function testFormTag_setMethodGet() {

        $this->tagMethods = [
            [
                'name' => 'method',
                'parameters' => ['GET']
            ]
        ];

        $this->performTagTest();
    }

    /** Tests:
    %tagCall%->method('DELETE')
     */
    public function testFormTag_setMethodDelete() {

        $this->tagMethods = [
            [
                'name' => 'method',
                'parameters' => ['DELETE']
            ]
        ];

        $this->performTagTest();
    }

    /** Tests:
    %tagCall%->method('PUT')
     */
    public function testFormTag_setMethodPut() {

        $this->tagMethods = [
            [
                'name' => 'method',
                'parameters' => ['PUT']
            ]
        ];

        $this->performTagTest();
    }

    /** Tests:
    %tagCall%->method('PATCH')
     */
    public function testFormTag_setMethodPatch() {

        $this->tagMethods = [
            [
                'name' => 'method',
                'parameters' => ['PATCH']
            ]
        ];

        $this->performTagTest();
    }

    /** Tests:
    %tagCall%->requestObject(DefaultTestRequest::class)
     */
    public function testFormTag_setRequestObject() {

        $this->tagMethods = [
            [
                'name' => 'requestObject',
                'parameters' => [DefaultTestRequest::class]
            ]
        ];

        $this->performTagTest();
    }

    /** Tests:
    %tagCall%->requestObject(HoneypotTestRequest::class)
     */
    public function testFormTag_Honeypot() {

        $this->tagMethods = [
            [
                'name' => 'requestObject',
                'parameters' => [HoneypotTestRequest::class]
            ]
        ];

        $this->honeypotFieldName = md5(csrf_token());

        $this->performTagTest();
    }

    /** Tests:
    %tagCall%->requestObject(CaptchaTestRequest::class)
     */
    public function testFormTag_Captcha() {

        $this->tagMethods = [
            [
                'name' => 'requestObject',
                'parameters' => [CaptchaTestRequest::class]
            ]
        ];

        $this->hasCaptcha = true;

        $this->performTagTest();
    }

}
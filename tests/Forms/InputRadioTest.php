<?php
/**
 * Created by PhpStorm.
 * User: geraldb
 * Date: 29.10.2015
 * Time: 13:24
 */

namespace FormBuilderTests\Forms;

use FormBuilderTests\Traits\Tests\TestsCheckedAttribute;

class InputRadioTest extends InputTestCase
{
    use TestsCheckedAttribute;

    protected $tagFunction = 'radio';

    protected $tagParameters = [
        'value' => 'myFieldValue',
        'name' => 'myFieldName'
    ];

    protected $matchTagAttributes = ['type' => 'radio', 'class' => false];

    protected $wrapperMatcher = [
        'tag' => 'div',
        'attributes' => [
            'class' => 'radio',
        ]
    ];

    protected $labelMode = 'after';

}
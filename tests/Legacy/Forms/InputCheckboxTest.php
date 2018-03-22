<?php
/**
 * Created by PhpStorm.
 * User: geraldb
 * Date: 29.10.2015
 * Time: 13:24
 */

namespace FormFactoryTests\Legacy\Forms;

use FormFactoryTests\Legacy\Traits\Tests\TestsCheckedAttribute;

class InputCheckboxTest extends InputTestCase
{
    use TestsCheckedAttribute;

    protected $tagFunction = 'checkbox';

    protected $tagParameters = [
        'name' => 'myFieldName',
        'value' => 'myFieldValue'
    ];

    protected $matchTagAttributes = ['type' => 'checkbox', 'class' => false];

    protected $wrapperMatcher = [
        'tag' => 'div',
        'attributes' => [
            'class' => 'checkbox',
            'data-field-wrapper' => '1'
        ]
    ];

    protected $labelMode = 'after';

}
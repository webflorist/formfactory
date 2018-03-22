<?php
/**
 * Created by PhpStorm.
 * User: geraldb
 * Date: 29.10.2015
 * Time: 13:24
 */

namespace FormFactoryTests\Legacy\Forms;

use FormFactoryTests\Legacy\Traits\Tests\TestsAutocompleteAttribute;
use FormFactoryTests\Legacy\Traits\Tests\TestsValueAttribute;

class ButtonSubmitTest extends ButtonTest
{

    protected $tagFunction = 'submit';

    protected $context = 'primary';

    protected $buttonType = 'submit';

}
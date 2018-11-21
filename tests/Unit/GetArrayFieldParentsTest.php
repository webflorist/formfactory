<?php

namespace FormFactoryTests\Unit;

use Form;
use FormFactoryTests\TestCase;
use Webflorist\FormFactory\Utilities\FormFactoryTools;

class GetArrayFieldParentsTest extends TestCase
{

    public function testNoArrayParents()
    {
        $this->assertEquals(
            [],
            FormFactoryTools::getArrayFieldParents('myFieldName')
        );
    }

    public function testMultipleArrayParents()
    {
        $this->assertEquals(
            [
                'myFirstParent[mySecondParent][myThirdParent]',
                'myFirstParent[mySecondParent]',
                'myFirstParent'
            ],
            FormFactoryTools::getArrayFieldParents('myFirstParent[mySecondParent][myThirdParent][myActualField]')
        );
    }
}
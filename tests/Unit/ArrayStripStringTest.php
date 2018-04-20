<?php

namespace FormFactoryTests\Unit;

use Form;
use FormFactoryTests\TestCase;
use Nicat\FormFactory\Utilities\FormFactoryTools;

class ArrayStripStringTest extends TestCase
{

    public function testNonArray()
    {
        $this->assertEquals(
            'myFieldName',
            FormFactoryTools::arrayStripString('myFieldName')
        );
    }

    public function testSimpleArray()
    {
        $this->assertEquals(
            'myFieldName',
            FormFactoryTools::arrayStripString('myFieldName[]')
        );
    }

    public function testSimpleNumericalArray()
    {
        $this->assertEquals(
            'myFieldName',
            FormFactoryTools::arrayStripString('myFieldName[0]')
        );
    }

    public function testComplexArray()
    {
        $this->assertEquals(
            'mySubFieldName',
            FormFactoryTools::arrayStripString('myFieldName[mySubFieldName]')
        );
    }

    public function testSimpleDynamicListTemplateArray()
    {
        $this->assertEquals(
            'myFieldName',
            FormFactoryTools::arrayStripString('myFieldName[%groupe9776a39728387e4dddd5ebc0872b1bcitemID%]')
        );
    }

    public function testComplexDynamicListTemplateArray()
    {
        $this->assertEquals(
            'mySubFieldName',
            FormFactoryTools::arrayStripString('myFieldName[%groupe9776a39728387e4dddd5ebc0872b1bcitemID%][mySubFieldName]')
        );
    }
}
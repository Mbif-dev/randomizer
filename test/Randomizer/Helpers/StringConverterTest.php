<?php

namespace Randomizer\Test\Helpers;

use Randomizer\Helpers\StringConverter;
use PHPUnit\Framework\TestCase;

final class StringConverterTest extends TestCase
{

    public function testChangeToCamelCaseConverter()
    {
        $this->assertEquals(StringConverter::changeToCamelCase('Example_fields'), 'exampleFields');
        $this->assertEquals(StringConverter::changeToCamelCase('example_Fields'), 'exampleFields');
        $this->assertEquals(StringConverter::changeToCamelCase('Example_Fields'), 'exampleFields');
        $this->assertEquals(StringConverter::changeToCamelCase('ExampleFields'), 'exampleFields');
    }

} 
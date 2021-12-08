<?php

namespace Randomizer\Test\DB;

use Randomizer\DB\DbTableField;
use PHPUnit\Framework\TestCase;

final class DbTableFieldTest extends TestCase
{

    public function testCorrectFields()
    {
        $field = new DbTableField(['Field' => 'emp_no', 'Type' => 'int(11)', 'Null' => 'NO', 'Key' => 'PRI', 'Default' => '', 'Extra' => '']);
        $this->assertEquals($field->getLenght(), 11);
        $this->assertEquals($field->getType(), 'int');
        $this->assertEquals($field->getName(), 'emp_no');
        $this->assertTrue($field->isPrimaryKey());
        $this->assertFalse($field->isText());
        
        $field2 = new DbTableField(['Field' => 'name', 'Type' => 'varchar(15)', 'Key' => '']);
        $this->assertEquals($field2->getLenght(), 15);
        $this->assertEquals($field2->getType(), 'varchar');
        $this->assertFalse($field2->isPrimaryKey());
        $this->assertTrue($field2->isText());
        
        $field3 = new DbTableField(['Field' => 'name', 'Type' => 'text', 'Key' => '']);
        $this->assertEmpty($field3->getLenght());
        $this->assertEquals($field3->getType(), 'text');
        $this->assertTrue($field3->isText());

        $field4 = new DbTableField(['Field' => 'name', 'Type' => 'char(5)', 'Key' => '']);
        $this->assertTrue($field4->isText());
    }

    public function testInCorrectField()
    {
        $this->expectException(\InvalidArgumentException::class);
        $field = new DbTableField(['Null' => 'NO', 'Default' => '', 'Extra' => '']);
    }
}

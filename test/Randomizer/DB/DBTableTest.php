<?php

namespace Randomizer\Test\DB;

use Randomizer\DB\DbTable;
use PHPUnit\Framework\TestCase;

final class DbTableTest extends TestCase
{

    public function testTable()
    {
        $fields=[];
        $fields[]=['Field' => 'emp_no', 'Type' => 'int(11)', 'Null' => 'NO', 'Key' => 'PRI', 'Default' => '', 'Extra' => ''];
        $table = new DbTable('example',$fields);
        
        $this->assertFalse($table->hasTextField('html'));
        $this->assertFalse($table->hasTextField('emp_no'));
        
        $fields[]=['Field' => 'html', 'Type' => 'varchar(15)', 'Null' => 'NO', 'Key' => 'PRI', 'Default' => '', 'Extra' => ''];
        
        $table2= new DbTable('example2',$fields);
        $this->assertTrue($table2->hasTextField('html'));
    }

}  
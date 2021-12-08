<?php

namespace Randomizer\Test\Helpers;

use Randomizer\DB\DbTable;
use Randomizer\DataToRandomize;
use PHPUnit\Framework\TestCase;

final class DataToRandomizeTest extends TestCase
{
    
    private static $table;
    
    public static function setUpBeforeClass():void
    {
        $fields=[];
        $fields[]=['Field' => 'emp_no', 'Type' => 'int(11)', 'Null' => 'NO', 'Key' => 'PRI', 'Default' => '', 'Extra' => ''];      
        $fields[]=['Field' => 'html', 'Type' => 'varchar(15)', 'Null' => 'NO', 'Key' => 'PRI', 'Default' => '', 'Extra' => ''];        
        self::$table= new DbTable('example',$fields);;
    } 

    public function testHasTable()
    {
        $dataToRandomize=new DataToRandomize();        
        $this->assertFalse($dataToRandomize->hasTables());
        $dataToRandomize->addTable(self::$table);
        $this->assertTrue($dataToRandomize->hasTables());
    }

    public function testGetTablesNames(){
        $dataToRandomize=new DataToRandomize(); 
        $this->assertEquals($dataToRandomize->getTablesNames(), []);
        $dataToRandomize->addTable(self::$table);
        $this->assertEquals($dataToRandomize->getTablesNames(), ['example']);
    }

    public function testGetInCorrectTable()
    {
        $this->expectException(\InvalidArgumentException::class);
        $dataToRandomize=new DataToRandomize(); 
        $dataToRandomize->addTable(self::$table);
        $dataToRandomize->getTable('table_not_exists');
    }

    public function testGetCorrectTable()
    {       
        $dataToRandomize=new DataToRandomize(); 
        $dataToRandomize->addTable(self::$table);
        $this->assertInstanceOf(DbTable::class,  $dataToRandomize->getTable('example'));
    }
} 
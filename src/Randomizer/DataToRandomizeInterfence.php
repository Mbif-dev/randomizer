<?php

namespace Randomizer;
use Randomizer\DB\DbTable;

interface DataToRandomizeInterfence{

    public function addTable(DbTable $table):void;

    public function addFakeDataForFields(string $tableName,array $fakeData, string $fieldName,string $fieldWhere):void;

    public function getTablesNames():array;

    public function getTableFields(string $tableName):array;

    public function getTable(string $tableName):DbTable;

    public function hasTables():bool;
}
<?php

namespace Randomizer;

use Randomizer\DataToRandomizeInterfence;
use Randomizer\DB\DbTable;

class DataToRandomize implements DataToRandomizeInterfence
{

    private $tablesToRandomize = [];

    public function addTable(DbTable $table): void
    {
        $tableName = $table->getName();
        if (!$this->isExistsTable($tableName)) {
            $this->tablesToRandomize[$tableName]['table'] = $table;
        }
    }

    public function getTable(string $tableName): DbTable
    {

        if (!$this->isExistsTable($tableName))
            throw new \InvalidArgumentException('The table ' . $tableName . ' is not prepare to data randomize');

        return  $this->tablesToRandomize[$tableName]['table'];
    }

    public function getTableFields(string $tableName): array
    {

        if (!$this->isExistsTable($tableName))
            throw new \InvalidArgumentException('The table ' . $tableName . ' is not prepare to data randomize');

        return  $this->tablesToRandomize[$tableName]['fields'];
    }

    public function addFakeDataForFields(string $tableName, array $fakeData, string $fieldName, string $fieldWhere): void
    {
        if (!$this->isExistsTable($tableName))
            throw new \InvalidArgumentException('The table ' . $tableName . ' is not prepare to data randomize');

        $key = (!empty($fieldWhere)) ? 'withWhere' : 'withOutWhere';
        $this->tablesToRandomize[$tableName]['fields'][$key][$fieldName] = $fakeData;
        
        if ((!empty($fieldWhere)))
            $this->tablesToRandomize[$tableName]['fields'][$key][$fieldName]['where'] = $fieldWhere;
    }

    public function getTablesNames(): array
    {
        return array_keys($this->tablesToRandomize);
    }

    public function hasTables(): bool
    {
        return (count($this->tablesToRandomize) > 0) ? true : false;
    }

    private function isExistsTable(string $tableName): bool
    {
        return (array_key_exists($tableName, $this->tablesToRandomize)) ? true : false;
    }
}

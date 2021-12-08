<?php

namespace Randomizer;


use Randomizer\DB\DbTable;
use Randomizer\FakeDataManager;
use Randomizer\DataToRandomize;
use Randomizer\Db\DbRandomizerManager;

class Randomizer
{

    private $dbh;
    private $allTables;
    private $fakerManager;
    private $dataToRandomize;

    public function __construct(\PDO $dbh)
    {
        $this->dbh = $dbh;
        $this->dbRandomizerManager=new DbRandomizerManager($this->dbh,'MySQL');
    }


    public function run(array $fieldsToRandomize):string
    {
        
        $this->allTables = $this->dbRandomizerManager->getAllDbTables();
        $this->fakerManager = new FakeDataManager();
        $this->dataToRandomize= new DataToRandomize();
        $this->setTablesToRandomize($fieldsToRandomize);
        return $this->dbRandomizerManager->runRandomize($this->dataToRandomize);     
    }

    private function setTablesToRandomize($fieldsToRandomize): void
    {
        
        foreach ($this->allTables as $table) {
            foreach ($fieldsToRandomize as $field) {
                $isFieldWithWhere = $this->isFieldWithWhere($field);
                    $fieldToCheck = ($isFieldWithWhere) ? $field[0] : $field;
                    $fieldsWhere = ($isFieldWithWhere) ? $field[1] : '';
                if ($this->isToRandomize($table, $fieldToCheck)) {
                    $this->prepareDataToRandomize($table, $fieldToCheck, $fieldsWhere);
                }
            }
        }
    }

    private function isFieldWithWhere($field)
    {
        return (is_array($field)) ? true : false;
    }

    private function prepareDataToRandomize(DbTable $table, string $field, string $fieldsWhere): void
    {

        $tableName = $table->getName();
        $this->dataToRandomize->addTable($table);
        $fakeData=$this->fakerManager->getFakeData($table->getField($field));
        $this->dataToRandomize->addFakeDataForFields($tableName,$fakeData,$field,$fieldsWhere);
    }

    private function isToRandomize(DbTable $table, string $fieldToCheck): bool
    {

        if ($table->hasTextField($fieldToCheck) && !$table->getField($fieldToCheck)->isPrimaryKey())
            return true;

        return false;
    }
}

<?php

namespace Randomizer\DB\MySQL;

use Randomizer\DB\DbTable;
use Randomizer\DB\DbTableField;

class QueryBulider
{

    private $usedFields = [];
    private $generatedDataForFields = [];

    public function createTemporaryTable(DbTable $table, array $fields): string
    {
        $this->usedFields=[];
        $this->generatedDataForFields=[];
        $sql = 'CREATE TEMPORARY TABLE tmp_' . $table->getName() . ' ( tmp_id INT unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY';
        $sql .= $this->prepareSQLToCreateFieldsForTemporaryTable('withOutWhere', $fields, $table);
        $sql .= $this->prepareSQLToCreateFieldsForTemporaryTable('withWhere', $fields, $table);
        $sql .= ')AUTO_INCREMENT=1; ';
        return $sql;
    }

    public function dropTemporaryTable(DbTable $table): string
    {
        $sql = 'DROP TEMPORARY TABLE tmp_' . $table->getName() . ';';
        return $sql;
    }

    public function addDataToTemporaryTable(DbTable $table, array $fields): string
    {
        $sql = '';
        $sql = $this->prepareSQLValuesForCreateTemporaryTable($sql, $fields) . ';';
        $sql = 'INSERT INTO tmp_' . $table->getName() . ' VALUES ' . $sql;
        return $sql;
    }

    public function updateTable(DbTable $table, string $field, string $where = '')
    {

        if ($where != '')
            $where = ' WHERE ' . $where;
        $sql = 'UPDATE ' . $table->getName() . ' SET ' . $field . '= (SELECT ' . $field . ' FROM tmp_' . $table->getName() . ' ORDER BY RAND() LIMIT 1)' . $where . ';';
        
        return $sql;
    }

    private function prepareSQLToCreateFieldsForTemporaryTable(string $key, array $fields, DbTable $table): string
    {
        $sql = '';
        if (isset($fields[$key])) {

            foreach ($fields[$key] as $name => $val) {
                $field = $table->getField($name);
                if (!in_array($name, $this->usedFields)) {
                    $this->usedFields[] = $name;
                    $sql .= ', ' . $name . ' ' . $this->getFieldTypeForCreateTable($field);
                }
            }
        }
        return $sql;
    }

    private function prepareSQLValuesForCreateTemporaryTable(string $sql, array $fields): string
    {
        $values = [];
        $values = $this->getFakeValues('withOutWhere', $fields, $values);
        $values = $this->getFakeValues('withWhere', $fields, $values);
        foreach ($values as $val)
            $tmp[] = "(0," . implode(',', $val) . ")";

        return $sql . implode(',', $tmp);
    }

    private function getFakeValues(string $key, array $fields, array $values): array
    {
        if (isset($fields[$key])) {
            $i = 0;
            foreach ($fields[$key] as $field => $randomDatas) {
                $i = 0;
                if (!in_array($field, $this->generatedDataForFields)) {
                    $this->generatedDataForFields[]=$field;
                    foreach ($randomDatas as $key => $val) {
                        if (is_numeric($key)) {
                            $values[$i][] = "'" . $val . "'";
                            $i++;
                        }
                    }
                }
            }
        }
        return $values;
    }

    private function getFieldTypeForCreateTable(DbTableField $field): string
    {
        $type = $field->getType();
        $len = (!empty($field->getLenght())) ? '(' . $field->getLenght() . ')' : '';
        return $type . ' ' . $len;
    }
}

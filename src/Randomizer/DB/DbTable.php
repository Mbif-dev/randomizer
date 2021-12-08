<?php

namespace Randomizer\DB;

use Randomizer\DB\DbTableField;

class DbTable
{

    private $name;
    private  $fields = [];

    public function __construct(string $name, array $fields)
    {
        $this->name = $name;
        $this->loadFields($fields);
    }

    private function loadFields(array $fields): void
    {

        foreach ($fields as $field) {
            $newField = new DbTableField($field);
            $this->fields[$newField->getName()] = $newField;
        }
    }

    public function hasTextField($fieldName): bool
    {

        if (isset($this->fields[$fieldName]) && $this->fields[$fieldName]->isText())
            return true;

        return false;
    }

    public function getField($fieldName): DbTableField
    {
        return $this->fields[$fieldName];
    }

    public function getName(): string
    {
        return $this->name;
    }
}

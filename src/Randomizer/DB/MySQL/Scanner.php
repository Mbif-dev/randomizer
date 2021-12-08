<?php

namespace Randomizer\DB\MySQL;

use Randomizer\DB\DbScannerInterface;
use Randomizer\DB\DbTable;

class Scanner implements DbScannerInterface
{

    private $tables = [];
    private $dbh;

    public function __construct(\PDO $dbh)
    {
        $this->dbh = $dbh;
    }

    public function scanDb(): void
    {
        $this->findTables();
        $this->scanFields();
    }

    private function findTables(): void
    {
        $q = $this->dbh->query('SHOW TABLES');
        while ($tableName = $q->fetch(\PDO::FETCH_COLUMN)) {
            $this->tables[$tableName]=[];
        }
    }

    private function scanFields(): void
    {
        foreach ($this->tables as  $name => $val ) {
            $q = $this->dbh->query('DESCRIBE ' . $name);
            $fields = $q->fetchAll(\PDO::FETCH_ASSOC);
            $this->tables[$name] = new DbTable($name, $fields);
        }
    }

    public function getDbTables(): array
    {
        return $this->tables;
    }
}

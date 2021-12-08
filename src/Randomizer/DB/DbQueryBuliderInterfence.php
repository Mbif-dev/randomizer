<?php

namespace Randomizer\DB;

use Randomizer\Db\DbTable;

interface QueryBulider
{
    public function createTemporaryTable(DbTable $table, array $fields): string;
    public function dropTemporaryTable(DbTable $table): string;
    public function addDataToTemporaryTable(DbTable $table, array $fields): string;
    public function updateTableWithCommonFields(DbTable $table, string $field, string $where=''): string;
}
